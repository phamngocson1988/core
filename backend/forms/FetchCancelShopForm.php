<?php

namespace backend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;

class FetchCancelShopForm extends FetchShopForm
{
    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$table.id", 
            "$table.customer_id", 
            "$table.customer_name",
            "$table.quantity",
            "$table.doing_unit", 
            "$table.total_unit",
            "$table.game_id", 
            "$table.game_title", 
            "$table.created_at", 
            "$table.completed_at", 
            "$table.state", 
            "$table.status", 
            "$table.saler_id", 
            "$table.orderteam_id", 
            "$table.request_cancel_description",
            "$table.request_cancel_time",
            "$table.request_cancel",
        ]);
        
        $condition = [
            "$table.id" => $this->id,
            "$table.customer_id" => $this->customer_id,
            "$table.saler_id" => $this->saler_id,
            "$table.orderteam_id" => $this->orderteam_id,
            "$table.payment_method" => $this->payment_method,
            "$table.game_id" => $this->game_id,
        ];
        $condition = array_filter($condition);
        $command->where($condition);
        $command->andWhere(['NOT IN', "$table.status", [
            Order::STATUS_COMPLETED,
            Order::STATUS_CONFIRMED,
            Order::STATUS_DELETED,
        ]]);
        $command->andWhere(['OR',
            ["$table.status" => Order::STATUS_CANCELLED],
            ["$table.request_cancel" => 1]
        ]);

        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date]);
        }
        $command->indexBy(function ($row) use(&$index){
           return ++$index;
        });
        // die($command->createCommand()->getRawSql());
        $this->_command = $command;
    }

    public function count()
    {
        return $this->getCommand()->count();
    }

    public function countCancelling() 
    {
        $table = Order::tableName();
        $command = Order::find()->where(["$table.request_cancel" => 1]);
        $command->andWhere(['NOT IN', "$table.status", [
            Order::STATUS_COMPLETED,
            Order::STATUS_CONFIRMED,
            Order::STATUS_DELETED,
            Order::STATUS_CANCELLED
        ]]);
        return $command->count();
    }

    public function getSumQuantity()
    {
        $table = Order::tableName();
        return $this->getCommand()->sum("$table.quantity");
    }

    public function getAverageCompletedTime()
    {
        $table = Order::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.created_at, $table.completed_at)");
    }

    public function getAverageSupplierCompletedTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at)");
    }

    public function getAveragePendingTime()
    {
        $table = Order::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.created_at, $table.pending_at)");
    }

    public function getAverageApprovedTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at)");
    }

    public function getAverageProcessingTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, $supplierTable.completed_at)");
    }

    public function getAverageWaitingTime()
    {
        $table = Order::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.created_at, IFNULL($table.processing_at, '$now'))");
    }

    public function getAverageDistributedTime()
    {
        $table = Order::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.pending_at, IFNULL($table.distributed_at, '$now'))");
    }

    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'order-list' . date('His') . '.xlsx';
        $names = [
            'Mã đơn hàng',
            'Tên khách hàng',
            'Shop Game',
            'Số lượng nạp',
            'Số gói',
            // 'Tổng TG chờ',
            // 'TG duyệt',
            // 'TG phân phối',
            // 'TG nhận đơn',
            'Lý do huỷ',
            'Người bán hàng',
            'Nhân viên đơn hàng',
            'Trạng thái',
        ];
        $characters = [ 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ];
        $titles = array_combine($characters, $names);
        $totalRow = $command->count();
        $startRow = 4;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $header = [
            "A1:{$endColumn}1" => sprintf('THỐNG KÊ ĐƠN HÀNG CANCELLED'),
            "A2:{$endColumn}2" => sprintf('Thời gian thống kê: %s đến %s', $this->start_date, $this->end_date),
        ];
        $footer = [
            // "A$footerRow" => sprintf('Tổng: %s', $command->count()),
            // "G$footerRow" => sprintf('Tổng: %s', number_format($command->sum('order.doing'), 1)),
        ];
        
        $data = [];
        // $command->orderBy(['order.created_at' => SORT_DESC]);
        $models = $command->all();
        foreach ($models as $model) {
            $data[] = [
                '#' . $model->id, 
                $model->customer_name,
                $model->game_title, 
                $model->total_unit, 
                $model->quantity, 
                // $model->waiting_time, 
                // $model->pending_time, 
                // $model->distributed_time, 
                // $model->approved_time, 
                $model->request_cancel_description, 
                ($model->saler) ? $model->saler->name : '',
                ($model->orderteam) ? $model->orderteam->name : '',
                $model->isCancelledOrder() ? 'Cancelled' : 'Cancelling',
            ];
        }
        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'writerClass' => '\PHPExcel_Writer_Excel5', //\PHPExcel_Writer_Excel2007
            'sheets' => [
                'Report by transaction' => [
                    'class' => 'common\components\export\excel\ExcelSheet',//'codemix\excelexport\ExcelSheet',
                    // 'heading' => $heading,
                    'header' => $header,
                    'footer' => $footer,
                    'data' => $data,
                    'startRow' => $startRow,
                    'titles' => $titles,
                    'styles' => [
                        $rangeTitle => [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            ],
                        ],
                        $rangeTable => [
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        ],
                    ],
                    
                    'on beforeRender' => function ($event) {
                        $sender = $event->sender;
                        $sheet = $sender->getSheet();
                        $sender->renderHeader();
                        $sender->renderFooter();
                        $titles = $sender->getTitles();
                        $columns = array_keys($titles);
                        foreach ($columns as $column) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }
                    },
                    'on afterRender' => function($event) {
                        $sheet = $event->sender->getSheet();
                        $sheet->setSelectedCell("A1");
                    }
                ],
            ],
        ]);
        $file->send($fileName);
    }

}
