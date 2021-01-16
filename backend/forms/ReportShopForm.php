<?php

namespace backend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\User;
use backend\models\UserReseller;
use backend\models\Game;
use backend\models\OrderComplains;
use backend\models\OrderSupplier;
use backend\models\Promotion;
use common\models\Country;

class ReportShopForm extends FetchShopForm
{
    public $date_time_type;

    public function rules()
    {
        return [
            [['saler_id','supplier_id','orderteam_id','game_id','start_date','end_date','status', 'date_time_type'], 'safe']
        ];
    }

    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();

        if ($this->supplier_id) {
            $command->innerJoin($supplierTable, "{$table}.id = {$supplierTable}.order_id AND $supplierTable.supplier_id = " . $this->supplier_id);
        } else {
            $command->leftJoin($supplierTable, "{$table}.id = {$supplierTable}.order_id");
        }
        $command->where(["IN", "$supplierTable.status", [
            OrderSupplier::STATUS_APPROVE,
            OrderSupplier::STATUS_PROCESSING,
            OrderSupplier::STATUS_COMPLETED,
            OrderSupplier::STATUS_CONFIRMED,
        ]]);

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$table.id", 
            "$table.customer_id", 
            "$table.customer_name", 
            "$table.game_id",
            "$table.game_title", 
            "$table.payment_method", 
            "$table.price", 
            "$table.sub_total_price", 
            "$table.total_price", 
            "$table.promotion_id", 
            "$table.rate_usd", 
            "$table.status", 
            "$table.saler_id", 
            "$table.orderteam_id", 
            "$table.created_at",
            "$supplierTable.completed_at as supplier_completed_at",
            "$table.confirmed_at as order_confirmed_at",
            "$supplierTable.supplier_id",
            "$supplierTable.price as supplier_price", 
            "$supplierTable.quantity as supplier_quantity", 
            "$supplierTable.doing", 
            "$supplierTable.requested_at", 
            "$supplierTable.approved_at", 
            "$supplierTable.processing_at", 
            "$supplierTable.completed_at", 
            "$supplierTable.confirmed_at", 
            "TIMESTAMPDIFF(MINUTE , $table.created_at, IFNULL($table.pending_at, '$now')) as approved_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at) as supplier_approved_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.processing_at) as supplier_pending_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, $supplierTable.completed_at) as supplier_processing_time", 
            "TIMESTAMPDIFF(MINUTE , $supplierTable.completed_at, $supplierTable.confirmed_at) as supplier_confirmed_time", 
            "TIMESTAMPDIFF(MINUTE , $table.created_at, IFNULL($table.completed_at, '$now')) as order_completed_time", 
            "TIMESTAMPDIFF(MINUTE , IFNULL($supplierTable.approved_at, '$now'), IFNULL($supplierTable.completed_at, '$now')) as supplier_completed_time", 
            // "TIMESTAMPDIFF(MINUTE , IFNULL($table.pending_at, '$now'), IFNULL($supplierTable.requested_at, '$now')) as distributed_time", 
            "$supplierTable.distributed_time"
        ]);
        
        $condition = [
            "$table.saler_id" => $this->saler_id,
            "$table.orderteam_id" => $this->orderteam_id,
            "$table.game_id" => $this->game_id,
            "$table.status" => $this->status,
        ];
        $condition = array_filter($condition);
        $command->andWhere($condition);
        if ($this->date_time_type) {
            $type = $this->date_time_type;
            if ($this->start_date) {
                $command->andWhere(['>=', "$table.$type", $this->start_date]);
            }
            if ($this->end_date) {
                $command->andWhere(['<=', "$table.$type", $this->end_date]);
            }
        }
        // die($command->createCommand()->getRawSql());
        $this->_command = $command;
    }

    // Export function
    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'order-list' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Mã đơn hàng',
            'B' => 'Tên khách hàng',
            'C' => 'Cấp bậc KH',
            'D' => 'Quốc gia',
            'E' => 'Shop game',
            'F' => 'Phương thức nạp',
            'G' => 'Số gói',
            'H' => 'Cổng thanh toán',
            'I' => 'Thời điểm tạo',
            'J' => 'Thời điểm NCC nhận đơn',
            'K' => 'Thời điểm hoàn thành',
            'L' => 'Thời điểm xác nhận',
            'M' => 'Tổng TG Hoàn Thành',
            'N' => 'Tổng TG NCC hoàn thành',
            'O' => 'TG duyệt',
            'P' => 'TG phân phối',
            'Q' => 'TG nhận đơn', 
            'R' => 'TG login',    
            'S' => 'TG nạp',  
            'T' => 'TG xác nhận', 
            'U' => 'Trạng thái',  
            'V' => 'Sai thông tin',   
            'W' => 'Nội dung sai thông tin',  
            'X' => 'NV Hổ Trợ',   
            'Y' => 'NV Phân Phối',    
            'Z' => 'Nhà Cung Cấp',
            'AA' => 'Giá bán ( Kcoin )',
            'AB' => 'Giá đơn hàng ( Kcoin )',
            'AC' => 'Phí phát sinh ( Kcoin )',
            'AD' => 'Khuyến mãi ( Kcoin )',
            'AE' => 'KH thanh Toán ( Kcoin )',
            'AF' => 'Thực nhận ( Kcoin )',
            'AG' => 'Mã khuyến mãi',
            'AH' => 'Tỷ giá ( VND/Kcoin )',
            'AI' => 'Doanh thu ( VND )',
            'AJ' => 'Giá mua ( VND ) ',
            'AK' => 'Thanh toán NCC ( VND )',
            'AL' => 'Lợi nhuận ( VND )',
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
        $customer = $this->getCustomer();
        $saler = User::findOne($this->saler_id);
        $orderteam = User::findOne($this->orderteam_id);
        $game = Game::findOne($this->game_id);
        $supplier = User::findOne($this->supplier_id);
        $allPaymentMethods = $this->fetchPaymentMethods();
        $paymentMethod = ArrayHelper::getValue($allPaymentMethods, $this->payment_method, '');
        $header = [
            "A1:{$endColumn}1" => sprintf('THỐNG KÊ ĐƠN HÀNG'),
            "A2:{$endColumn}2" => sprintf('Thời gian thống kê: %s đến %s', $this->start_date, $this->end_date),
            // "A3:{$endColumn}3" => sprintf('Khách hàng: %s', $customer ? $customer->name : ''),
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
        $command->orderBy(['order.created_at' => SORT_DESC]);
        $models = $command->asArray()
        ->indexBy(function ($row) use(&$index){
           return ++$index;
        })
        ->all();
        $orderIds = ArrayHelper::getColumn($models, 'id');
        // Get Complain
        $complains = OrderComplains::find()
        ->where(['in', 'order_id', $orderIds])             
        ->andWhere(['in', 'object_name', [OrderComplains::OBJECT_NAME_ADMIN, OrderComplains::OBJECT_NAME_SUPPLIER]])
        ->groupBy(['order_id'])
        ->select(['order_id', 'content'])
        ->all();
        $existStaffComplainIds = ArrayHelper::getColumn($complains, 'order_id');
        $contentComplainIds = ArrayHelper::map($complains, 'order_id', 'content');
        // Get Reseller
        $userIds = ArrayHelper::getColumn($models, 'customer_id');
        $users = User::find()
        ->where(['in', 'id', $userIds])
        ->indexBy('id')->all();

        // Get resellers 
        $resellers = UserReseller::find()
        ->where(['in', 'user_id', $userIds])
        ->indexBy('user_id')->all();

        // Get salers 
        $salerIds = ArrayHelper::getColumn($models, 'saler_id');
        $salers = User::find()
        ->where(['in', 'id', $salerIds])
        ->indexBy('id')->all();

        // order team
        $orderteamIds = ArrayHelper::getColumn($models, 'orderteam_id');
        $orderteams = User::find()
        ->where(['in', 'id', $orderteamIds])
        ->indexBy('id')->all();

        // Supplier
        $supplierIds = ArrayHelper::getColumn($models, 'supplier_id');
        $suppliers = User::find()->where(['in', 'id', $supplierIds])
        ->indexBy('id')->all();

        foreach ($models as $model) {
            $user = $users[$model['customer_id']];
            $reseller = ArrayHelper::getValue($resellers, $user->id);
            $resellerLevel = $reseller ? $reseller->getLevelLabel() : '';

            $country = Country::findOne($user->country_code);
            $countryName = $country ? $country->country_name : '';
            
            $supplier = ArrayHelper::getValue($suppliers, $model['supplier_id']);
            $saler = ArrayHelper::getValue($salers, $model['saler_id']);
            $orderteam = ArrayHelper::getValue($orderteams, $model['orderteam_id']);

            // Promotion
            $promotion = $model['promotion_id'] ? Promotion::findOne($model['promotion_id']) : null;
            $data[] = [
                '#' . $model['id'], 
                $model['customer_name'],
                $resellerLevel,
                $countryName,
                $model['game_title'], 
                $model['payment_method'],
                $model['doing'],
                $model['payment_method'],
                $model['created_at'],
                $model['approved_at'],
                $model['supplier_completed_at'],
                $model['order_confirmed_at'],
                $model['order_completed_time'],
                $model['supplier_completed_time'],
                $model['approved_time'],
                $model['distributed_time'],
                $model['supplier_approved_time'],
                $model['supplier_pending_time'],
                $model['supplier_processing_time'],
                $model['supplier_confirmed_time'],
                $model['status'],
                in_array($model['id'], $existStaffComplainIds) ? 'X' : '',
                html_entity_decode(strip_tags(ArrayHelper::getValue($contentComplainIds, $model['id'], ''))),
                $saler ? $saler->getName() : '',
                $orderteam ? $orderteam->getName() : '',
                $supplier ? $supplier->getName() : '',




                $model['price'],
                $model['price'] * $model['doing'],
                0, //$model['total_price'] - $model['sub_total_price'],
                0,
                $model['price'] * $model['doing'],
                '',
                $promotion ? $promotion->code : '',
                $model['rate_usd'],
                $model['price'] * $model['supplier_quantity'] * $model['rate_usd'],
                $model['supplier_price'],
                $model['supplier_price'] * $model['supplier_quantity'],
                ($model['price'] * $model['supplier_quantity'] * $model['rate_usd']) - ($model['supplier_price'] * $model['supplier_quantity'])
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

    public function fetchDateTimeType()
    {
        return [
            'created_at' => 'Thời điểm tạo đơn',
            'completed_at' => 'Thời điểm hoàn thành',
            'confirmed_at' => 'Thời điểm confirmed'
        ];
    }
}
