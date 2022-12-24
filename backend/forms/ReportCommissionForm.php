<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\OrderCommission;
use backend\models\User;
use backend\models\Game;
use common\components\helpers\StringHelper;

class ReportCommissionForm extends Model
{
    public $user_ids = [];
    public $start_date;
    public $end_date;
    public $game_ids = [];

    private $_command;
    /**
     * Array: [["user_id", "username", "name", "commission_type", "role", "user_commission"], ...]
     */
    protected $reportData = [];

    public function init()
    {
        if (!$this->start_date) $this->start_date = date('Y-m-01');
        if (!$this->end_date) $this->end_date = date('Y-m-d');
    }
    
    public function run()
    {
        $command = $this->getCommand();
        $this->reportData = $command->all();
    }

    protected function createCommand()
    {
        $commissionTable = OrderCommission::tableName();
        $userTable = User::tableName();
        $command = OrderCommission::find();
        $command->leftJoin($userTable, "{$commissionTable}.user_id = {$userTable}.id");
        if (count($this->user_ids)) {
            $command->andWhere(['user_id' => $this->user_ids]);
        }
        if ($this->game_ids && count($this->game_ids)) {
            $command->andWhere(['game_id' => $this->game_ids]);
        }
        $command->andWhere(["between", "$commissionTable.created_at", $this->start_date . " 00:00:00",  $this->end_date . " 23:59:59"]);

        $command->select([
            "$commissionTable.user_id",
            "$commissionTable.order_id",
            "$commissionTable.quantity",
            "$commissionTable.commission_type", 
            "$commissionTable.role",
            "$commissionTable.user_commission", 
            "$commissionTable.created_at", 
            "$commissionTable.description", 
            "$userTable.username", 
            "$userTable.name", 
        ]);
        // echo $command->createCommand()->getRawSql();die;
        $command->asArray();
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchUsers()
    {
        $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
        $orderTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('orderteam_manager');

        $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
        $salerTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('saler_manager');

        $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');

        $userIds = array_merge($orderTeamIds, $orderTeamManagerIds, $salerTeamIds, $salerTeamManagerIds, $adminTeamIds);
        $userIds = array_unique($userIds);
        $users = User::findAll($userIds);

        return ArrayHelper::map($users, 'id', 'name');   
    }

    public function fetchGames()
    {
        $games = Game::find()->select(['id', 'title'])->all();
        return ArrayHelper::map($games, 'id', 'title');   
    }

    public function getData() 
    {
        return (array)$this->reportData;
    }

    /**
     * @return [[user_id => 1, username => 'abc', 'total' => 10]]
     */
    public function getCommissionByUser()
    {
        $groups = ArrayHelper::index($this->getData(), null, 'user_id');
        $result = [];
        foreach (array_keys($groups) as $key) {
            $rows = $groups[$key];
            $row = reset($rows);
            $commissionRows = array_filter($rows, function($row){
                return $row['commission_type'] === OrderCommission::COMMSSION_TYPE_ORDER;
            });
            $selloutRows = array_filter($rows, function($row){
                return $row['commission_type'] === OrderCommission::COMMSSION_TYPE_SELLOUT;
            });
            $result[] = [
                'user_id' => $key,
                'name' => $row['name'] ? $row['name'] : $row['username'], 
                OrderCommission::COMMSSION_TYPE_ORDER => array_sum(ArrayHelper::getColumn($commissionRows, 'user_commission')),
                OrderCommission::COMMSSION_TYPE_SELLOUT => array_sum(ArrayHelper::getColumn($selloutRows, 'user_commission'))
            ];
        }

        return $result;
    }

    public function export($fileName = null)
    {
        $dataByUser = $this->getCommissionByUser();
        $fileName = ($fileName) ? $fileName : 'order-commission' . date('His') . '.xlsx';
        $names = [
            'Nhân viên',
            'Sell out',
            'Hoa hồng',
            'Tổng',
        ];
        $characters = [ 'A', 'B', 'C', 'D' ];
        $titles = array_combine($characters, $names);
        $totalRow = count($dataByUser);
        $startRow = 6;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow + 1);

        $users = $this->fetchUsers();
        if ($this->user_ids && count($this->user_ids)) {
            $users = array_intersect_key(
                $users,  // the array with all keys
                array_flip($this->user_ids) // keys to be extracted
            );
        }
        $games = $this->fetchGames();
        if ($this->game_ids && count($this->game_ids)) {
            $games = array_intersect_key(
                $games,  // the array with all keys
                array_flip($this->game_ids) // keys to be extracted
            );
        }

        $header = [
            "A1:{$endColumn}1" => sprintf('THỐNG KÊ HOA HỒNG NHÂN VIÊN'),
            "A2:{$endColumn}2" => sprintf('Thời gian thống kê: %s đến %s', $this->start_date, $this->end_date),
            "A3:{$endColumn}3" => sprintf('Nhân viên: %s', implode(", ", $users)),
            "A4:{$endColumn}4" => sprintf('Games: %s', implode(", ", $games)),
        ];

        $commissions = $this->getData();
        $orderIds = ArrayHelper::getColumn($commissions, 'order_id');
        $orderIds = array_unique($orderIds);
        $sumSelloutCommission = array_sum(ArrayHelper::getColumn($dataByUser, OrderCommission::COMMSSION_TYPE_SELLOUT));
        $sumOrderCommission = array_sum(ArrayHelper::getColumn($dataByUser, OrderCommission::COMMSSION_TYPE_ORDER));
        $footer = [
            "A$footerRow" => sprintf('Total Orders: %s', count($orderIds)),
            "B$footerRow" => StringHelper::numberFormat($sumSelloutCommission, 0),
            "C$footerRow" => StringHelper::numberFormat($sumOrderCommission, 0),
            "D$footerRow" => ''
        ];
        
        $data = [];
        foreach ($dataByUser as $userId => $commission) {
            $data[] = [
                $commission['name'],
                StringHelper::numberFormat($commission[OrderCommission::COMMSSION_TYPE_SELLOUT], 0),
                StringHelper::numberFormat($commission[OrderCommission::COMMSSION_TYPE_ORDER], 0),
                StringHelper::numberFormat($commission[OrderCommission::COMMSSION_TYPE_SELLOUT] + $commission[OrderCommission::COMMSSION_TYPE_ORDER], 0)
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
