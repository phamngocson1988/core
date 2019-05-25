<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\UserWallet;
use common\models\User;

class ReportByBalanceForm extends Model
{
    public $start_date;
    public $end_date;
    public $user_id;
    private $_user;
    private $_command;

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-t')],
            ['user_id', 'trim']
        ];
    }

    public function fetch()
    {
        // Find all users in period
        $command = $this->getUserCommand();
        $models = $command->all();//print_r($models);echo $command->createCommand()->getRawSql();die;
        $users = [];
        foreach ($models as $model) {
            $userId = $model->user_id;

            // find topup/ withdraw
            $topupCommand = $this->getCommand();
            $topupCommand->andWhere(['type' => UserWallet::TYPE_INPUT]);
            $topupCommand->andWhere(['user_id' => $userId]);
            $totalTopup = $topupCommand->sum('coin');

            $withdrawCommand = $this->getCommand();
            $withdrawCommand->andWhere(['type' => UserWallet::TYPE_OUTPUT]);
            $withdrawCommand->andWhere(['user_id' => $userId]);
            $totalWithdraw = $withdrawCommand->sum('coin');

            // find balance
            $balanceAtStart = UserWallet::find();
            $balanceAtStart->orderBy(['payment_at' => SORT_DESC]);
            $balanceAtStart->where(['user_id' => $userId]);
            $balanceAtStart->andWhere(['<=', 'payment_at', $this->start_date . " 23:59:59"]);
            $balanceAtStartModel = $balanceAtStart->one();
            $balanceAtStartNumber = ($balanceAtStartModel) ? $balanceAtStartModel->balance : 0;

            $balanceAtEnd = UserWallet::find();
            $balanceAtEnd->orderBy(['payment_at' => SORT_DESC]);
            $balanceAtEnd->where(['user_id' => $userId]);
            $balanceAtEnd->andWhere(['<=', 'payment_at', $this->end_date . " 23:59:59"]);
            $balanceAtEndModel = $balanceAtEnd->one();
            $balanceAtEndNumber = ($balanceAtEndModel) ? $balanceAtEndModel->balance : 0;

            $users[$model->user_id]['name'] = $model->user->name;
            $users[$model->user_id]['topup'] = $totalTopup;
            $users[$model->user_id]['withdraw'] = $totalWithdraw;
            $users[$model->user_id]['balance_start'] = $balanceAtStartNumber;
            $users[$model->user_id]['balance_end'] = $balanceAtEndNumber;
        }
        return $users;
    }

    public function export($fileName = null)
    {
        // $command = $this->getUserCommand();
        $report = $this->fetch();
        $fileName = ($fileName) ? $fileName : 'report-by-balance' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Thứ tự',
            'B' => 'Khách hàng',
            'C' => 'Số tiền nạp',
            'D' => 'Số tiền mua hàng',
            'E' => 'Số dư ban đầu',
            'F' => 'Số dư hiện tại',
        ];
        $totalRow = count($report);
        $startRow = 5;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);;
        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'REPORT BY BALANCE';
        $header = [
            'A2:I2' => sprintf('Thời gian: %s - %s', $this->start_date, $this->end_date),
            'A3:I3' => sprintf('Khách hàng: %s', ($this->user_id) ? $this->user->name : ''),
        ];
        
        $data = [];
        $no = 0;
        foreach ($report as $userId => $model) {
            $data[] = [
                $no + 1, 
                $model['name'], 
                $model['topup'], 
                $model['withdraw'], 
                $model['balance_start'], 
                $model['balance_end'], 
            ];
        }

        $file = \Yii::createObject([
		    'class' => 'codemix\excelexport\ExcelFile',
		    'sheets' => [
		        'Report by transaction' => [
                    'class' => 'common\components\export\excel\ExcelSheet',//'codemix\excelexport\ExcelSheet',
                    'heading' => $heading,
                    'header' => $header,
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

    public function exportDetail($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'report-by-balance' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Thứ tự',
            'B' => 'Mã GD/Mã đơn hàng',
            'C' => 'Loại giao dịch',
            'D' => 'Thời gian hoàn thành',
            'E' => 'Kcoin',
            'F' => 'Số dư ban đầu',
            'G' => 'Số dư hiện tại',
        ];
        $totalRow = $command->count();
        $startRow = 5;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);;
        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'REPORT DETAIL BY BALANCE';
        $header = [
            'A2:I2' => sprintf('Thời gian: %s - %s', $this->start_date, $this->end_date),
            'A3:I3' => sprintf('Khách hàng: %s', ($this->user_id) ? $this->user->name : ''),
        ];
        $footer = [
            "E$footerRow" => 'Tổng: ' . $command->sum('coin')
        ];
        
        $data = [];
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $no + 1, 
                $model->description, 
                ($model->type == UserWallet::TYPE_INPUT) ? "Nạp tiền" : "Rút tiền", 
                $model->payment_at, 
                $model->coin, 
                $model->balance - $model->coin,
                $model->balance 
            ];
        }

        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
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

    protected function createCommand()
    {
        $command = UserWallet::find();
        $command->where(["status" => UserWallet::STATUS_COMPLETED]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'payment_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'payment_at', $this->end_date . " 23:59:59"]);
        }

        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }

        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return clone $this->_command;
    }

    public function getUserCommand()
    {
        $command = $this->getCommand();
        $command->select(['id', 'user_id']);
        $command->with('user');
        $command->groupBy('user_id');
        return $command;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

}
