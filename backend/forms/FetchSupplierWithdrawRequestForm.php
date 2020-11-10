<?php
namespace backend\forms;

use yii\base\Model;
use backend\models\SupplierWithdrawRequest;
use Yii;
use backend\models\User;

class FetchSupplierWithdrawRequestForm extends Model
{
    public $status;
    public $start_date;
    public $end_date;

    private $_command;

    protected function createCommand()
    {
        $this->status = (array)$this->status;
        if (!$this->status) {
            $this->status = $this->getDefaultStatusList();
        }
        $command = SupplierWithdrawRequest::find()->where(["IN", 'status', $this->status]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', sprintf("%s 00:00:00", $this->start_date)]);
        }

        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', sprintf("%s 23:59:59", $this->end_date)]);
        }
        // die($command->createCommand()->getRawSql());
        return $command;
    }


    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    public function getStatusList()
    {
        return [
            SupplierWithdrawRequest::STATUS_REQUEST => "Gửi yêu cầu",
            SupplierWithdrawRequest::STATUS_APPROVE => "Đã phê duyệt",
            SupplierWithdrawRequest::STATUS_DONE => "Đã hoàn tất",
            SupplierWithdrawRequest::STATUS_CANCEL => "Hủy bỏ",
        ];
    }

    public function getDefaultStatusList()
    {
        return [
            SupplierWithdrawRequest::STATUS_REQUEST,
            SupplierWithdrawRequest::STATUS_APPROVE
        ];
    }

    // Export function
    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'withdraw-list' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Mã yêu cầu',
            'B' => 'Nhà cung cấp',
            'C' => 'Số tiền rút',
            'D' => 'Số dư khả dụng',
            'E' => 'Thông tin tài khoản',
            'F' => 'Ngày tạo',
            'G' => 'Hình ảnh',
            'H' => 'Trạng thái',
            'I' => 'Chi chú',
        ];
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

        // $heading = 'THỐNG KÊ ĐƠN HÀNG';
        $this->status = (array)$this->status;
        if (!$this->status) {
            $this->status = $this->getDefaultStatusList();
        }
        $header = [
            "A1:{$endColumn}1" => sprintf('CÁC YÊU CẦU RÚT TIỀN'),
            "A2:{$endColumn}2" => sprintf('Thời gian thống kê: %s đến %s', $this->start_date, $this->end_date),
            "A3:{$endColumn}3" => sprintf('Các trạng thái: %s', implode(", ", $this->status)),
            // "A4:{$endColumn}4" => sprintf('Nhân viên sale: %s', $saler ? $saler->name : ''),
            // "A5:{$endColumn}5" => sprintf('Nhân viên đơn hàng: %s', $orderteam ? $orderteam->name : ''),
            // "A6:{$endColumn}6" => sprintf('Tên game: %s', $game ? $game->title : ''),
            // "A7:{$endColumn}7" => sprintf('Nhà cung cấp: %s', ($supplier) ? $supplier->name : ''),
            // "A8:{$endColumn}8" => sprintf('Ngày xác nhận từ: %s', ($this->confirmed_from) ? $this->confirmed_from : ''),
            // "A9:{$endColumn}9" => sprintf('Ngày xác nhận đến: %s', ($this->confirmed_from) ? $this->confirmed_from : ''),
            // "A10:{$endColumn}10" => sprintf('Phương thức thanh toán: %s', $paymentMethod),
        ];
        $footer = [
            // "A$footerRow" => sprintf('Tổng: %s', $command->count()),
            // "G$footerRow" => sprintf('Tổng: %s', number_format($command->sum('order.doing'), 1)),
        ];
        
        $data = [];
        $command->orderBy(['created_at' => SORT_DESC]);
        $models = $command->all();

        foreach ($models as $model) {
            $user = User::findOne($model->supplier_id);
            $data[] = [
                $model->getId(), 
                sprintf("%s (#%s)", $user->name, $user->id),
                number_format($model->amount),
                number_format($model->available_balance),
                sprintf("(%s) %s - %s", $model->bank_code, $model->account_number, $model->account_name),
                $model->created_at,
                $model->evidence ? $model->evidence : '',
                $model->getStatusLabel(false),
                $model->note
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