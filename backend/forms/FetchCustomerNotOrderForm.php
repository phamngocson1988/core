<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Country;
use backend\models\Game;
use backend\models\Order;
use yii\helpers\ArrayHelper;

class FetchCustomerNotOrderForm extends Model
{
    public $user_id;
    public $created_start;
    public $created_end;
    public $no_purchase_start;
    public $no_purchase_end;
    public $saler_id;
    public $is_reseller;
    protected $_customer;
    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'customer-list' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Thứ tự',
            'B' => 'Khách hàng',
            'C' => 'Ngày sinh',
            'D' => 'Email',
            'E' => 'Số điện thoại',
            'F' => 'Ngày đăng ký',
            'G' => 'Quốc tịch',
            'H' => 'Đơn hàng cuối cùng',
            'I' => 'Tổng tiền nạp',
            'J' => 'Tổng tiền mua',
            'K' => 'Reseller / Khách hàng',
            'L' => 'Đại lý / Người bán hàng',
        ];
        $totalRow = $command->count();
        $startRow = 5;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'CUSTOMER LIST';
        // $game = Game::findOne($this->game_id);
        // $saler = User::findOne($this->saler_id);
        // $resellerStatus = User::getResellerStatus();
        // $customerType = ArrayHelper::getValue($resellerStatus, $this->is_reseller, '');
        // $country = Country::findOne($this->country_code);

        $from = ($this->no_purchase_start) ? $this->no_purchase_start : '~';
        $to = ($this->no_purchase_end) ? $this->no_purchase_end : date('Y-m-d');
        $header = [
            'A2:L2' => "Không có đơn hàng từ $from đến $to",
            // 'A3:L3' => sprintf('Sinh nhật: %s - %s', $this->birthday_start, $this->birthday_end),
            // 'A4:L4' => sprintf('Có đơn hàng trong khoảng: %s - %s', $this->purchase_start, $this->purchase_end),
            // 'A5:L5' => sprintf('Tổng giá trị đơn hàng: %s - %s', $this->total_purchase_start, $this->total_purchase_end),
            // 'A6:L6' => sprintf('Quốc gia: %s', ($country) ? $country->country_name : ''),
            // 'A7:L7' => sprintf('Đã từng mua game: %s', ($game) ? $game->title : ''),
            // 'A8:L8' => sprintf('Nhân viên bán hàng: %s', ($saler) ? $saler->name : ''),
            // 'A9:L9' => sprintf('Loại người dùng: %s', $customerType),
        ];
        // $footer = [
        //     "F$footerRow" => sprintf('Tổng: %s', $command->sum('total_unit_purchase')),
        //     "H$footerRow" => sprintf('Tổng: %s', $command->sum('total_price_purchase')),
        // ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $no + 1, 
                $model->name,
                $model->birthday, 
                $model->email,
                $model->phone,
                $model->created_at,
                $model->getCountryName(),
                ($model->order) ? $model->order->created_at : '',
                $model->getWalletTopupAmount(),
                $model->getWalletWithdrawAmount(),
                ($model->isReseller()) ? 'Reseller' : 'Customer',
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
                    // 'footer' => $footer,
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

    protected function createCommand()
    {
        $userTable = User::tableName();
        $orderTable = Order::tableName();
        $orderStatus = [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_COMPLETED];

        $orderCommand = Order::find()->select(['customer_id'])->groupBy(['customer_id']);
        if ($this->no_purchase_start && $this->no_purchase_end) {
            $orderCommand->where(['BETWEEN', 'created_at', $this->no_purchase_start . ' 00:00:00', $this->no_purchase_end . ' 23:59:59']);
        } else {
            if ($this->no_purchase_start) {
                $orderCommand->where(['>=', 'created_at', $this->no_purchase_start . ' 00:00:00']);
            }
            if ($this->no_purchase_end) {
                $orderCommand->where(['<=', 'created_at', $this->no_purchase_end . ' 23:59:59']);
            }
        }
        // echo $orderCommand->createCommand()->getRawSql();die;
        $orders = $orderCommand->asArray()->all();
        $customerIds = array_column($orders, 'customer_id');

        $command = User::find()
        ->where(['NOT IN', 'id', $customerIds])
        ->with('order');
        // echo $command->createCommand()->getRawSql();die;
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchSalers()
    {
        $member = Yii::$app->authManager->getUserIdsByRole('saler');
        $manager = Yii::$app->authManager->getUserIdsByRole('sale_manager');
        $admin = Yii::$app->authManager->getUserIdsByRole('admin');

        $salerTeamIds = array_merge($member, $manager, $admin);
        $salerTeamIds = array_unique($salerTeamIds);
        $salerTeamObjects = User::findAll($salerTeamIds);
        $salerTeam = ArrayHelper::map($salerTeamObjects, 'id', 'email');
        return $salerTeam;
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = User::findOne($this->user_id);
        }
        return $this->_customer;
    }
}
