<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\PaymentTransaction;
use common\models\User;

class ReportByTransactionForm extends PaymentTransaction
{
    public $start_date;
    public $end_date;
    public $count_order;
    public $is_reseller;

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-t')],
        ];
    }

    private $_command;
    
    public function fetch()
    {
        if (!$this->validate()) return [];
        $command = $this->getCommand();
        return $command->all();
    }

    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'report-by-transaction' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Thứ tự',
            'B' => 'Thời gian',
            'C' => 'Khách hàng',
            'D' => 'Mã giao dịch',
            'E' => 'Khuyến mãi Kcoin',
            'F' => 'Số lượng Kcoin',
            'G' => 'Giảm giá',
            'H' => 'Số tiền',
            'I' => 'Trạng thái'
        ];
        $totalRow = $command->count();
        $startRow = 7;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);;
        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'REPORT BY TRANSACTION';
        $header = [
            'A2:I2' => sprintf('Thời gian: %s - %s', $this->start_date, $this->end_date),
            'A3:I3' => sprintf('Khách hàng: %s', ($this->user_id) ? $this->user->name : ''),
            'A4:I4' => sprintf('Mã khuyến mãi: %s', $this->discount_code),
            'A5:I5' => sprintf('Mã giao dịch: %s', $this->auth_key),
        ];
        $footer = [
            "F$footerRow" => sprintf('Tổng: %s', $command->sum('total_coin')),
            "H$footerRow" => sprintf('Tổng: %s', $command->sum('total_price')),
        ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $no + 1, 
                $model->payment_at, 
                $model->user->name,
                $model->auth_key,
                $model->discount_coin,
                $model->total_coin,
                $model->discount_price,
                $model->total_price,
                $model->status,
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

    protected function createCommand()
    {
        $command = self::find();
        $table = self::tableName();
        $command->where(["$table.status" => self::STATUS_COMPLETED]);

        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date . " 23:59:59"]);
        }

        if ($this->user_id) {
            $command->andWhere(["$table.user_id" => $this->user_id]);
        }

        if ($this->discount_code) {
            $command->andWhere(["$table.discount_code" => $this->discount_code]);
        }

        if ($this->auth_key) {
            $command->andWhere(["$table.auth_key" => $this->auth_key]);
        }

        if ($this->is_reseller) {
            $userTable = User::tableName();
            $command->leftJoin($userTable, "$table.user_id = $userTable.id")->andWhere(["$userTable.is_reseller" => $this->is_reseller]);
        }//echo $command->createCommand()->getRawSql();die;
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
}
