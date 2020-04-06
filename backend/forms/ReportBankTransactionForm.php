<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\BankTransaction;
use backend\models\BankAccount;
use backend\models\Bank;

class ReportBankTransactionForm extends Model
{
    public $bank_id;
    public $bank_account_id;
    public $created_by;
    public $from_date;
    public $to_date;

    private $_command;

    protected function createCommand()
    {
        $table = BankTransaction::tableName();
        $command = BankTransaction::find();
        $command->where(["{$table}.status" => BankTransaction::STATUS_COMPLETED]);
        if ($this->bank_id) {
            $command->andWhere(["{$table}.bank_id" => $this->bank_id]);
        }
        if ($this->bank_account_id) {
            $command->andWhere(["{$table}.bank_account_id" => $this->bank_account_id]);
        }
        if ($this->created_by) {
            $command->andWhere(["{$table}.created_by" => $this->created_by]);
        }
        if ($this->from_date) {
            $command->andWhere([">=", "{$table}.updated_at", sprintf("%s 00:00:00", $this->from_date)]);
        }
        if ($this->to_date) {
            $command->andWhere(["<=", "{$table}.updated_at", sprintf("%s 23:59:59", $this->to_date)]);
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
    
    public function fetchBank()
    {
        $banks = Bank::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }

    public function fetchBankAccount()
    {
        $account = BankAccount::find()->all();
        return ArrayHelper::map($account, 'id', 'name');
    }

    public function fetchUser()
    {
        $users = User::find()->all();
        return ArrayHelper::map($users, 'id', 'name');
    }

    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'customer-list' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Thứ tự',
            'B' => 'Ngày hoàn thành',
            'C' => 'Ngân hàng',
            'D' => 'Tài khoản',
            'E' => 'Loại giao dịch',
            'F' => 'Số tiền',
            'G' => 'Trạng thái',
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

        $heading = 'Thống kê';
        $bank = Bank::findOne($this->bank_id);
        $header = [
            'A2:L2' => sprintf('Khoản thời gian thống kê: %s - %s', $this->from_date, $this->to_date),
            'A3:L3' => sprintf('Ngân hàng: %s', $bank->name),
        ];
        $footer = [
            "F$footerRow" => sprintf('Tổng: %s', $command->sum('amount')),
        ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                (string)($no + 1), 
                $model->updated_at,
                $model->bank->name, 
                sprintf("% - %s", $model->bankAccount->account_name, $model->bankAccount->account_number),
                $model->isTypeIn() ? 'Nạp tiền' : 'Chuyển tiền',
                (string)abs($model->amount),
                'Đã hoàn thành'
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
}
