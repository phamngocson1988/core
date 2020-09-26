<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\User;
use backend\models\Game;
use backend\models\Supplier;
use backend\models\OrderSupplier;
use backend\models\OrderComplains;
use common\components\helpers\FormatConverter;

class FetchOrderForm extends Model
{
    public $q;
    public $customer_id;
    public $game_id;
    public $start_date;
    public $end_date;
    public $saler_id;
    public $orderteam_id;
    public $supplier_id;
    public $status;
    public $agency_id;
    public $is_reseller;
    public $request_cancel;
    public $customer_phone;
    public $completed_from;
    public $completed_to;
    public $confirmed_from;
    public $confirmed_to;
    public $payment_method;
    public function rules()
    {
        return [
            [['q', 'customer_phone'], 'trim'],
            [['game_id', 'customer_id', 'saler_id', 'orderteam_id', 'status'], 'safe'],
            [['start_date', 'end_date'], 'safe'],
            [['completed_from', 'completed_to'], 'safe'],
            [['confirmed_from', 'confirmed_to'], 'safe'],
            [['supplier_id', 'agency_id', 'is_reseller', 'payment_method'], 'safe'],
        ];
    }

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();
        $command->select(["$table.*", "TIMESTAMPDIFF(SECOND , $table.created_at, $table.process_start_time) as processing_waiting_time", "TIMESTAMPDIFF(SECOND , $table.created_at, $table.process_end_time) as completed_waiting_time"]);
        $supplierTable = OrderSupplier::tableName();
        
        if ($this->q) {
            $command->andWhere(["$table.id" => $this->q]);
        }
        if ($this->customer_id) {
            $command->andWhere(["$table.customer_id" => $this->customer_id]);
        }
        if ($this->customer_phone) {
            $command->andWhere(["LIKE", "$table.customer_phone", $this->customer_phone]);
        }
        if ($this->game_id) {
            $command->andWhere(["$table.game_id" => $this->game_id]);
        }
        if ($this->payment_method) {
            $command->andWhere(["$table.payment_method" => $this->payment_method]);
        }
        if ($this->saler_id) {
            $command->andWhere(["$table.saler_id" => $this->saler_id]);
        }
        if ($this->supplier_id) {
            $command->innerJoin($supplierTable, "{$table}.id = {$supplierTable}.order_id");
            $command->andWhere(["$supplierTable.supplier_id" => $this->supplier_id]);
            $command->andWhere(["IN", "$supplierTable.status", [
                OrderSupplier::STATUS_COMPLETED,
                OrderSupplier::STATUS_CONFIRMED,
            ]]);
        }
        if ($this->request_cancel) {
            $command->andWhere(["$table.request_cancel" => $this->request_cancel]);
        }
        if ($this->orderteam_id) {
            if ($this->orderteam_id == '-1') {
                $command->andWhere(["IS", "$table.orderteam_id", null]);
            } else {
                $command->andWhere(["$table.orderteam_id" => $this->orderteam_id]);
            }
        }
        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date]);
        }

        if ($this->completed_from) {
            $command->andWhere(['>=', "$table.completed_at", $this->completed_from]);
        }
        if ($this->completed_to) {
            $command->andWhere(['<=', "$table.completed_at", $this->completed_to]);
        }

        if ($this->confirmed_from) {
            $command->andWhere(['>=', "$table.confirmed_at", $this->confirmed_from]);
        }
        if ($this->confirmed_to) {
            $command->andWhere(['<=', "$table.confirmed_at", $this->confirmed_to]);
        }

        if ($this->status) {
            if (is_array($this->status)) {
                $command->andWhere(['IN', "$table.status", $this->status]);
            } else {
                $command->andWhere(["$table.status" => $this->status]);
            }
        }
        if ($this->is_reseller) {
            $userTable = User::tableName();
            $command->leftJoin($userTable, "$table.customer_id = $userTable.id")->andWhere(["$userTable.is_reseller" => $this->is_reseller]);
        }
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getCustomer()
    {
        if ($this->customer_id) {
            return User::findOne($this->customer_id);
        }
    }

    public function getSaler()
    {
        if ($this->saler_id) {
            return User::findOne($this->saler_id);
        }
    }

    public function getOrderteam()
    {
        if ($this->orderteam_id) {
            return User::findOne($this->orderteam_id);
        }
    }

    public function getGame()
    {
        if ($this->game_id) {
            return Game::findOne($this->game_id);
        }
    }

    public function getStatus()
    {
        $list = Order::getStatusList();
        return $list;
    }

    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }

    public function fetchSuppliers()
    {
        $suppliers = Supplier::find()->all();
        $mapping = [];
        foreach ($suppliers as $supplier) {
            $user = $supplier->user;
            $mapping[$user->id] = sprintf("%s (%s)", $user->name, $user->email);
        }
        return $mapping;
    }

    public function fetchPaymentMethods()
    {
        $paygates = \backend\models\Paygate::find()->all();
        $list = ArrayHelper::map($paygates, 'identifier', 'name');
        $list['kinggems'] = 'King Coin';
        return $list;
    }

    // Export function
    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'order-list' . date('His') . '.xlsx';
        $columnKeys = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P'];
        $columnTitles = [
            'Mã đơn hàng',
            'Tên khách hàng',
            'Tên game',
            'Ngày xác nhận',
            'Cổng thanh toán',
            'Số lượng nạp',
            'Số gói',
            'Thời điểm NCC nhận đơn',
            'Thời gian chờ nạp',
            'Tổng thời gian chờ',
            'Người bán hàng',
            'Nhân viên đơn hàng',
            'Trạng thái',
            'Ngày tạo',
            'Nhà cung cấp',
            'Sai thông tin',
        ];
        $titles = array_combine($columnKeys, $columnTitles);
        $totalRow = $command->count();
        $startRow = 12;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'DANH SÁCH ĐƠN HÀNG ĐÃ XÁC NHẬN';
        $customer = $this->getCustomer();
        $saler = User::findOne($this->saler_id);
        $orderteam = User::findOne($this->orderteam_id);
        $game = Game::findOne($this->game_id);
        $supplier = User::findOne($this->supplier_id);
        $allPaymentMethods = $this->fetchPaymentMethods();
        $paymentMethod = ArrayHelper::getValue($allPaymentMethods, $this->payment_method, '');
        $header = [
            "A2:{$endColumn}2" => sprintf('Mã đơn hàng: %s', $this->q),
            "A3:{$endColumn}3" => sprintf('Khách hàng: %s', $customer ? $customer->name : ''),
            "A4:{$endColumn}4" => sprintf('Nhân viên sale: %s', $saler ? $saler->name : ''),
            "A5:{$endColumn}5" => sprintf('Nhân viên đơn hàng: %s', $orderteam ? $orderteam->name : ''),
            "A6:{$endColumn}6" => sprintf('Tên game: %s', $game ? $game->title : ''),
            "A7:{$endColumn}7" => sprintf('Nhà cung cấp: %s', ($supplier) ? $supplier->name : ''),
            "A8:{$endColumn}8" => sprintf('Ngày xác nhận từ: %s', ($this->confirmed_from) ? $this->confirmed_from : ''),
            "A9:{$endColumn}9" => sprintf('Ngày xác nhận đến: %s', ($this->confirmed_from) ? $this->confirmed_from : ''),
            "A10:{$endColumn}10" => sprintf('Phương thức thanh toán: %s', $paymentMethod),
        ];
        $footer = [
            "A$footerRow" => sprintf('Tổng: %s', $command->count()),
            "G$footerRow" => sprintf('Tổng: %s', number_format($command->sum('order.quantity'), 1)),
        ];
        
        $data = [];
        $command->orderBy(['order.created_at' => SORT_DESC]);
        $models = $command->all();
        $orderIds = ArrayHelper::getColumn($models, 'id');
        $complains = OrderComplains::find()
        ->where(['in', 'order_id', $orderIds])             
        ->andWhere(['in', 'object_name', ['supplier', 'admin']])
        ->groupBy(['order_id'])
        ->select(['order_id'])
        ->all();
        $existStaffComplainIds = ArrayHelper::getColumn($complains, 'order_id');
        foreach ($models as $model) {
            $suppliers = $model->suppliers;
            $supplierList = [];
            foreach ($suppliers as $supplier) {
                $supplierList[] = sprintf('%s (%s)', $supplier->user->name, $supplier->doing);
            }
            $data[] = [
                '#' . $model->id, 
                $model->customer_name,
                $model->game_title, 
                $model->confirmed_at,
                $model->payment_method,
                $model->total_unit,
                $model->quantity,
                $model->approved_at,
                FormatConverter::countDuration($model->processing_waiting_time),
                FormatConverter::countDuration($model->completed_waiting_time),
                ($model->saler) ? $model->saler->name : '',
                ($model->orderteam) ? $model->orderteam->name : '',
                $model->getStatusLabel(false),
                $model->created_at,
                implode(", ", $supplierList),
                in_array($model->id, $existStaffComplainIds) ? 'X' : ''
            ];
        }

        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'writerClass' => '\PHPExcel_Writer_Excel5', //\PHPExcel_Writer_Excel2007
            'sheets' => [
                'Report by transaction' => [
                    'class' => 'common\components\export\excel\ExcelSheet',//'codemix\excelexport\ExcelSheet',
                    'heading' => $heading,
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
