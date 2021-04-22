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
    public $role;

    public function rules()
    {
        return [
            [['saler_id','supplier_id','orderteam_id','game_id','start_date','end_date','status', 'date_time_type', 'role'], 'safe']
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
            "$table.payment_type", 
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
            "$supplierTable.doing as quantity", 
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
        $titles = $this->getColumns();
        $titleKeys = array_keys($titles);
        $excelKeys = $this->getExcelColumn($titles);
        $titles = array_combine($excelKeys, $titles);
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
        ];
        $footer = [
        ];
        
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

        $data = [];
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
            $item = [
                'id' => '#' . $model['id'],
                'customer_name' => $model['customer_name'],
                'reseller_level' => $resellerLevel,
                'country' => $countryName,
                'game_title' => $model['game_title'],
                'payment_type' => $model['payment_type'],
                'quantity' => $model['quantity'],
                'payment_method' => $model['payment_method'],
                'created_at' => $model['created_at'],
                'approved_at' => $model['approved_at'],
                'supplier_completed_at' => $model['supplier_completed_at'],
                'order_confirmed_at' => $model['order_confirmed_at'],
                'order_completed_time' => $model['order_completed_time'],
                'supplier_completed_time' => $model['supplier_completed_time'],
                'approved_time' => $model['approved_time'],
                'distributed_time' => $model['distributed_time'],
                'supplier_approved_time' => $model['supplier_approved_time'], 
                'supplier_pending_time' => $model['supplier_pending_time'],    
                'supplier_processing_time' => $model['supplier_processing_time'],  
                'supplier_confirmed_time' => $model['supplier_confirmed_time'], 
                'status' => $model['status'],  
                'is_wrong' => in_array($model['id'], $existStaffComplainIds) ? 'X' : '',   
                'wrong_information' => html_entity_decode(strip_tags(ArrayHelper::getValue($contentComplainIds, $model['id'], ''))),  
                'saler_name' => $saler ? $saler->getName() : '',   
                'orderteam_name' => $orderteam ? $orderteam->getName() : '',    
                'supplier_name' => $supplier ? $supplier->getName() : '',
                'price' => $model['price'],
                'total_price' => $model['price'] * $model['quantity'],
                'total_fee' => 0,
                'total_promotion' => 0,
                'total_paid' => $model['price'] * $model['quantity'],
                'total_received' => $model['price'] * $model['quantity'],
                'promotion_code' => $promotion ? $promotion->code : '',
                'exchange_rate' => $model['rate_usd'],
                'supplier_price' => $model['supplier_price'],
            ];
            $item = array_intersect_key($item, array_flip($titleKeys));
            $data[] = $item;
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

    protected function getColumns()
    {
        $titles = [
            'id' => 'Mã đơn hàng',
            'customer_name' => 'Tên khách hàng',
            'reseller_level' => 'Cấp bậc KH',
            'country' => 'Quốc gia',
            'game_title' => 'Shop game',
            'payment_type' => 'Phương thức nạp',
            'quantity' => 'Số gói',
            'payment_method' => 'Cổng thanh toán',
            'created_at' => 'Thời điểm tạo',
            'approved_at' => 'Thời điểm NCC nhận đơn',
            'supplier_completed_at' => 'Thời điểm hoàn thành',
            'order_confirmed_at' => 'Thời điểm xác nhận',
            'order_completed_time' => 'Tổng TG Hoàn Thành',
            'supplier_completed_time' => 'Tổng TG NCC hoàn thành',
            'approved_time' => 'TG duyệt',
            'distributed_time' => 'TG phân phối',
            'supplier_approved_time' => 'TG nhận đơn', 
            'supplier_pending_time' => 'TG login',    
            'supplier_processing_time' => 'TG nạp',  
            'supplier_confirmed_time' => 'TG xác nhận', 
            'status' => 'Trạng thái',  
            'is_wrong' => 'Sai thông tin',   
            'wrong_information' => 'Nội dung sai thông tin',  
            'saler_name' => 'NV Hổ Trợ',   
            'orderteam_name' => 'NV Phân Phối',    
            'supplier_name' => 'Nhà Cung Cấp',
            'price' => 'Giá bán ( Kcoin )',
            'total_price' => 'Giá đơn hàng ( Kcoin )',
            'total_fee' => 'Phí phát sinh ( Kcoin )',
            'total_promotion' => 'Khuyến mãi ( Kcoin )',
            'total_paid' => 'KH thanh Toán ( Kcoin )',
            'total_received' => 'Thực nhận ( Kcoin )',
            'promotion_code' => 'Mã khuyến mãi',
            'exchange_rate' => 'Tỷ giá ( VND/Kcoin )',
            'supplier_price' => 'Giá mua ( VND ) ',
        ];
        if ($this->role == 'saler') {
            unset($titles['supplier_name']);
            unset($titles['supplier_price']);
        } elseif ($this->role == 'orderteam') {
            unset($titles['customer_name']);
            unset($titles['reseller_level']);
            unset($titles['country']);
            unset($titles['payment_method']);
            unset($titles['price']);
            unset($titles['total_price']);
            unset($titles['total_fee']);
            unset($titles['total_promotion']);
            unset($titles['total_paid']);
            unset($titles['total_received']);
            unset($titles['promotion_code']);
        }
        return $titles;
    }

    protected function getExcelColumn($titles)
    {
        $alphas = range('A', 'Z');
        $totalColumns = count($titles);
        $roundAlphabel = ceil($totalColumns / count($alphas));
        $alphabelColumns = [];
        if ($roundAlphabel > 1) {
            $secondAlphas = range('A', 'Z');
            $roundAlphabel--;
            while ($roundAlphabel) {
                $letter = array_shift($secondAlphas);
                foreach (range('A', 'Z') as $newColumn) {
                    $alphas[] = $letter . $newColumn;
                }
                $roundAlphabel--;
            }
        }
        return array_slice($alphas, 0, $totalColumns);
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
