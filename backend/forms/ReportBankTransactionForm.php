<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\components\helpers\CommonHelper;
use backend\models\BankTransaction;
use backend\models\BankAccount;
use backend\models\Bank;
use backend\models\User;

class ReportBankTransactionForm extends Model
{
    public $currency;
    public $bank_id;
    public $bank_account_id;
    public $completed_by;
    public $from_date;
    public $to_date;

    public function rules()
    {
        return [
            ['currency', 'required']
        ];
    }

    private $_command;

    protected function createCommand()
    {
        $table = BankTransaction::tableName();
        $command = BankTransaction::find();
        $command->where(["{$table}.status" => BankTransaction::STATUS_COMPLETED]);
        $command->andWhere(['currency' => $this->currency]);
        if ($this->bank_id) {
            $command->andWhere(["{$table}.bank_id" => $this->bank_id]);
        }
        if ($this->bank_account_id) {
            $command->andWhere(["{$table}.bank_account_id" => $this->bank_account_id]);
        }
        if ($this->completed_by) {
            $command->andWhere(["{$table}.completed_by" => $this->completed_by]);
        }
        if ($this->from_date) {
            $command->andWhere([">=", "{$table}.completed_at", sprintf("%s 00:00:00", $this->from_date)]);
        }
        if ($this->to_date) {
            $command->andWhere(["<=", "{$table}.completed_at", sprintf("%s 23:59:59", $this->to_date)]);
        }
        $command->with('executor');
        $command->with('bank');
        $command->with('bankAccount');
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    /** Calculate relevant statistics **/
    public function getTotalTransaction()
    {
        $command = $this->getCommand();
        return $command->count();
    }

    public function getTotalAmount()
    {
        $command = $this->getCommand();
        return $command->sum('amount');
    }

    public function getTotalAmountByFromDate()
    {
        if (!$this->from_date) return 0;
        $table = BankTransaction::tableName();
        $command = BankTransaction::find();
        $command->where(["{$table}.status" => BankTransaction::STATUS_COMPLETED]);
        $command->andWhere(['currency' => $this->currency]);
        if ($this->bank_id) {
            $command->andWhere(["{$table}.bank_id" => $this->bank_id]);
        }
        if ($this->bank_account_id) {
            $command->andWhere(["{$table}.bank_account_id" => $this->bank_account_id]);
        }
        if ($this->completed_by) {
            $command->andWhere(["{$table}.completed_by" => $this->completed_by]);
        }
        if ($this->from_date) {
            $command->andWhere(["<", "{$table}.completed_at", sprintf("%s 00:00:00", $this->from_date)]);
        }
        return $command->sum('amount');
    }

    public function getTotalAmountByToDate()
    {
        $table = BankTransaction::tableName();
        $command = BankTransaction::find();
        $command->where(["{$table}.status" => BankTransaction::STATUS_COMPLETED]);
        $command->andWhere(['currency' => $this->currency]);
        if ($this->bank_id) {
            $command->andWhere(["{$table}.bank_id" => $this->bank_id]);
        }
        if ($this->bank_account_id) {
            $command->andWhere(["{$table}.bank_account_id" => $this->bank_account_id]);
        }
        if ($this->completed_by) {
            $command->andWhere(["{$table}.completed_by" => $this->completed_by]);
        }
        if ($this->to_date) {
            $command->andWhere(["<=", "{$table}.completed_at", sprintf("%s 00:00:00", $this->to_date)]);
        }
        return $command->sum('amount');
    }
    
    public function fetchBank()
    {
        $banks = Bank::find()->where(['currency' => $this->currency])->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }

    public function fetchBankAccount()
    {
        $account = BankAccount::find()->where(['currency' => $this->currency])->all();
        return ArrayHelper::map($account, 'id', function($a) {
            return sprintf("[%s] %s - %s", $a->bank->code, $a->account_name, $a->account_number);
        });
    }

    public function fetchUser()
    {
        $users = User::find()->all();
        return ArrayHelper::map($users, 'id', 'name');
    }

    public function fetchCurrency()
    {
        return CommonHelper::fetchCurrency();
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
            'G' => 'Ghi chú',
            'H' => 'Nhân viên thực hiện',
            'I' => 'Trạng thái',
        ];
        $totalRow = $command->count();
        $startRow = 10;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'Thống kê';
        $fromDate = $this->from_date ? $this->from_date : '~';
        $toDate = $this->from_date ? $this->from_date : '~';
        $header = [
            'A2:I2' => sprintf('Khoản thời gian thống kê: %s - %s', $fromDate, $toDate),
            'A3:I3' => sprintf('Tiền tệ: %s', $this->currency),
            'A4:I4' => sprintf('Số lượng giao dịch: %s', number_format($this->getTotalTransaction())),
            'A5:I5' => sprintf('Tổng tiền đầu kỳ: %s', number_format($this->getTotalAmountByFromDate())),
            'A6:I6' => sprintf('Tổng tiền trong kỳ: %s', number_format($this->getTotalAmount())),
            'A7:I7' => sprintf('Tổng tiền cuối kỳ: %s', number_format($this->getTotalAmountByToDate())),
        ];
        // $footer = [
        //     "F$footerRow" => sprintf('Tổng: %s', number_format($command->sum('amount'))),
        // ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $no + 1, 
                $model->updated_at,
                $model->bank->name, 
                sprintf("% - %s", $model->bankAccount->account_name, $model->bankAccount->account_number),
                $model->isTypeIn() ? 'Nạp tiền' : 'Chuyển tiền',
                number_format(abs($model->amount)),
                $model->description,
                $model->executor->name,
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

    public function exportBank($fileName = null)
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
            'G' => 'Ghi chú',
            'H' => 'Nhân viên thực hiện',
            'I' => 'Trạng thái',
        ];
        $totalRow = $command->count();
        $startRow = 10;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'Thống kê theo ngân hàng';
        $bank = Bank::findOne($this->bank_id);
        $bankName = ($bank) ? $bank->name : 'Tất cả ngân hàng';
        $fromDate = $this->from_date ? $this->from_date : '~';
        $toDate = $this->from_date ? $this->from_date : '~';
        $header = [
            'A2:I2' => sprintf('Khoản thời gian thống kê: %s - %s', $fromDate, $toDate),
            'A3:I3' => sprintf('Ngân hàng: %s - Tiền tệ: %s', $bankName, $this->currency),
            'A4:I4' => sprintf('Số lượng giao dịch: %s', number_format($this->getTotalTransaction())),
            'A5:I5' => sprintf('Tổng tiền đầu kỳ: %s', number_format($this->getTotalAmountByFromDate())),
            'A6:I6' => sprintf('Tổng tiền trong kỳ: %s', number_format($this->getTotalAmount())),
            'A7:I7' => sprintf('Tổng tiền cuối kỳ: %s', number_format($this->getTotalAmountByToDate())),
        ];
        // $footer = [
        //     "F$footerRow" => sprintf('Tổng: %s', number_format($command->sum('amount'))),
        // ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $no + 1, 
                $model->updated_at,
                $model->bank->name, 
                sprintf("% - %s", $model->bankAccount->account_name, $model->bankAccount->account_number),
                $model->isTypeIn() ? 'Nạp tiền' : 'Chuyển tiền',
                number_format(abs($model->amount)),
                $model->description,
                $model->executor->name,
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

    public function exportAccount($fileName = null)
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
            'G' => 'Ghi chú',
            'H' => 'Nhân viên thực hiện',
            'I' => 'Trạng thái',
        ];
        $totalRow = $command->count();
        $startRow = 10;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'Thống kê theo tài khoản ngân hàng';
        $account = BankAccount::findOne($this->bank_account_id);
        $accountName = ($account) ? sprintf("%s - %s", $account->account_name, $account->account_number) : 'Tất cả tài khoản';
        $fromDate = $this->from_date ? $this->from_date : '~';
        $toDate = $this->from_date ? $this->from_date : '~';
        $header = [
            'A2:I2' => sprintf('Khoản thời gian thống kê: %s - %s', $fromDate, $toDate),
            'A3:I3' => sprintf('Ngân hàng: %s - Tiền tệ: %s', $bankName, $this->currency),
            'A4:I4' => sprintf('Số lượng giao dịch: %s', number_format($this->getTotalTransaction())),
            'A5:I5' => sprintf('Tổng tiền đầu kỳ: %s', number_format($this->getTotalAmountByFromDate())),
            'A6:I6' => sprintf('Tổng tiền trong kỳ: %s', number_format($this->getTotalAmount())),
            'A7:I7' => sprintf('Tổng tiền cuối kỳ: %s', number_format($this->getTotalAmountByToDate())),
        ];
        // $footer = [
        //     "F$footerRow" => sprintf('Tổng: %s', number_format($command->sum('amount'))),
        // ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $no + 1, 
                $model->updated_at,
                $model->bank->name, 
                sprintf("% - %s", $model->bankAccount->account_name, $model->bankAccount->account_number),
                $model->isTypeIn() ? 'Nạp tiền' : 'Chuyển tiền',
                number_format(abs($model->amount)),
                $model->description,
                $model->executor->name,
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

    public function exportUser($fileName = null)
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
            'G' => 'Ghi chú',
            'H' => 'Nhân viên thực hiện',
            'I' => 'Trạng thái',
        ];
        $totalRow = $command->count();
        $startRow = 10;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'Thống kê theo nhân viên';
        $user = User::findOne($this->completed_by);
        $userName = ($user) ? $user->name : 'Tất cả nhân viên';
        $fromDate = $this->from_date ? $this->from_date : '~';
        $toDate = $this->from_date ? $this->from_date : '~';
        $header = [
            'A2:I2' => sprintf('Khoản thời gian thống kê: %s - %s', $fromDate, $toDate),
            'A3:I3' => sprintf('Ngân hàng: %s - Tiền tệ: %s', $bankName, $this->currency),
            'A4:I4' => sprintf('Số lượng giao dịch: %s', number_format($this->getTotalTransaction())),
            'A5:I5' => sprintf('Tổng tiền đầu kỳ: %s', number_format($this->getTotalAmountByFromDate())),
            'A6:I6' => sprintf('Tổng tiền trong kỳ: %s', number_format($this->getTotalAmount())),
            'A7:I7' => sprintf('Tổng tiền cuối kỳ: %s', number_format($this->getTotalAmountByToDate())),
        ];
        // $footer = [
        //     "F$footerRow" => sprintf('Tổng: %s', number_format($command->sum('amount'))),
        // ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $no + 1, 
                $model->updated_at,
                $model->bank->name, 
                sprintf("% - %s", $model->bankAccount->account_name, $model->bankAccount->account_number),
                $model->isTypeIn() ? 'Nạp tiền' : 'Chuyển tiền',
                number_format(abs($model->amount)),
                $model->description,
                $model->executor->name,
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
