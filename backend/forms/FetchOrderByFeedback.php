<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use backend\models\Game;
use backend\models\Order;
use yii\helpers\ArrayHelper;

/**
 * FetchCustomerForm
 */
class FetchOrderByFeedback extends Order
{
    public $created_at_start;
    public $created_at_end;
    private $_command;

    public function rules()
    {
        return [
            [['created_at_start', 'created_at_end', 'rating'], 'required']
        ];
    }

    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'order-has-feedback' . date('His') . '.xlsx';
        $titles = [
            'A' => 'Mã đơn',
            'B' => 'Ngày tạo',
            'C' => 'Người bán hàng',
            'D' => 'Người xử lý đơn',
            'E' => 'Nhà cung cấp',
            'F' => 'Khách hàng',
            'G' => 'Số điện thoại',
            'H' => 'Feedback',
        ];
        $totalRow = $command->count();
        $startRow = 3;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'ORDER HAS FEEDBACK';
        $header = [
            'A2:H2' => sprintf('Thời gian export: %s - %s', $this->created_at_start, $this->created_at_end),
        ];
        
        $data = [];
        
        foreach ($command->all() as $no => $model) {
            $data[] = [
                $model->id, 
                $model->created_at,
                ($model->saler) ? $model->saler->name : '', 
                ($model->orderteam) ? $model->orderteam->name : '',
                '',
                $model->customer_email,
                $model->customer_phone,
                $model->rating == 1 ? 'Like' : 'Dislike'
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
        $command = Order::find();
        if ($this->rating) {
            $command->andWhere(['IN', 'rating', (array)$this->rating]);
        }
        if ($this->created_at_start) {
            $command->andWhere(['>=', 'created_at', $this->created_at_start]);
        }
        if ($this->created_at_end) {
            $command->andWhere(['<=', 'created_at', $this->created_at_end]);
        }
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

}
