<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PaymentReality;
use common\components\helpers\TimeHelper;

class FetchPaymentRealityForm extends Model
{
    public $id;
    public $object_key;
    public $customer_id;
    public $payment_id;
    public $payer;
    public $status;
    public $date_type;
    public $start_date;
    public $end_date;

    private $_command;

    protected $_customer;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = PaymentReality::find();
        $condition = [
            'id' => preg_replace("/[^\d]/", "", $this->id),
            'object_key' => preg_replace("/[^\d]/", "", $this->object_key),
            'customer_id' => $this->customer_id,
            'payment_id' => $this->payment_id,
            'status' => $this->status,
        ];
        $condition = array_filter($condition);
        if (count($condition)) {
            $command->where($condition);
        }
        if ($this->payer) {
            $command->andWhere(['like', 'payer', $this->payer]);
        }

        if ($this->date_type) {
            switch ($this->date_type) {
                case 'created_at':
                case 'object_created_at':
                case 'payment_time': {
                    if ($this->start_date) {
                        $command->andWhere(['>=', $this->date_type, $this->start_date]);
                    }
            
                    if ($this->end_date) {
                        $command->andWhere(['<=', $this->date_type, $this->end_date]);
                    }
                    break;
                }
            }
        }
        $command->orderBy(['status' => SORT_ASC, 'updated_at' => SORT_DESC]);
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
        if (!$this->_customer) {
            $this->_customer = User::findOne($this->customer_id);
        }
        return $this->_customer;
    }

    public function fetchStatus()
    {
        return [
            PaymentReality::STATUS_PENDING => 'Pending',
            PaymentReality::STATUS_CLAIMED => 'Claimed',
        ];
    }

    // Export function
    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'order-list' . date('His') . '.xlsx';
        $columnKeys = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE'];
        $columnTitles = [
            'Mã nhận tiền',
            'Mã đơn hàng',
            'Khách hàng',
            'Kcoin /  Shop Game',
            'Ngày tạo đơn',
            'Ngày cập nhật',
            'Ngày Duyệt',
            'Ngày nhận hóa đơn',
            'TG chờ cập nhật hóa đơn',
            'TG chờ duyệt',
            'Cổng Thanh Toán',
            'TK người gửi',
            'Mã Tham Chiếu người gửi',
            'Mã Tham Chiếu người nhận',
            'Ghi chú từ khách hàng',
            'Giá đơn hàng (Kcoin)',
            'Phí giao dịch (Kcoin)',
            'Khuyến mãi (Kcoin)',
            'Mã khuyến mãi',
            'Quốc gia',
            'Tiền tệ',
            'Thực nhận (tiền tệ)',
            'Tỷ giá',
            'Cần thanh toán (Kcoin)',
            'Thực Nhận (Kcoin)',
            'Người Nhập',
            'Người duyệt',
            'Trạng Thái',
            'Hoá đơn người gửi',
            'Hoá đơn người nhận',
            'Ghi chú duyệt đơn hàng',
            'Ghi chú nhận tiền'
        ];
        $titles = array_combine($columnKeys, $columnTitles);
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

        $heading = 'THỐNG KÊ CẬP NHẬT HOÁ ĐƠN NHẬN TIỀN';
        $customer = $this->getCustomer();
        $saler = User::findOne($this->saler_id);
        $orderteam = User::findOne($this->orderteam_id);
        $game = Game::findOne($this->game_id);
        $supplier = User::findOne($this->supplier_id);
        $allPaymentMethods = $this->fetchPaymentMethods();
        $paymentMethod = ArrayHelper::getValue($allPaymentMethods, $this->payment_method, '');
        $header = [
            "A3:{$endColumn}3" => sprintf('Thời gian cập nhật: Từ %s đến %s', $this->start_date, $this->end_date)
        ];
        $footer = [
            // "A$footerRow" => sprintf('Tổng: %s', $command->count()),
            // "G$footerRow" => sprintf('Tổng: %s', number_format($command->sum('order.quantity'), 1)),
        ];
        
        $data = [];
        $models = $command->all();
        foreach ($models as $model) {
            $object = $model->object;
            $user = $model->user;
            $objectName = '--';
            if ($model->object_name == 'wallet') {
                $objectName = 'Kcoin';
            } elseif ($model->object_name == 'order') {
                $objectName = $object ? $object->game_title : 'Không tìm thấy đơn hàng tương ứng';
            }
            $waitingTime = '--';
            if ($model->payment_time) {
                $waitingTime = round(TimeHelper::timeDiff($model->payment_time, $model->created_at, 'minute'));
            }
            $confirmTime = '--';
            if ($model->isPending()) {
                $confirmTime = round(TimeHelper::timeDiff($model->created_at, $now, 'minute'));
            } elseif ($model->isClaimed()) {
                $confirmTime = round(TimeHelper::timeDiff($model->created_at, $model->confirmed_at, 'minute'));
            }
            $data[] = [
                $model->getId(),
                $model->isClaimed() ? $model->getObjectKey() : '--',
                $user ? $user->name : '--',
                $objectName,
                $object ? $object->created_at : '--',
                $model->created_at,
                $model->confirmed_at ? $model->confirmed_at : '--',
                $model->payment_time,
                $waitingTime,
                $confirmTime,
                $model->paygate,
                $model->payer,
                '', // ma tham chieu nguoi gui
                $model->payment_id,
                $model->payment_note,
                $model->kingcoin,
                '',// phi giao dich
                '', // khuyen mai
                '', // ma khuyen mai
                '', //quoc gia
                '', // tien te
                '', // thuc nhan (tien te)
                '', // tyr gia
                '', // can thanh toan
                '', //thuc nhan (kcoin)
                '', //nguoi nhap
                '', // nguoi duyet
                '', //trang thai
                '', // hoa don nguoi gui
                '', // hoa don nguoi nhan
                '', //ghi chu duyet don hang
                '' // ghi chu nhan tien
            ];
        }

        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'writerClass' => '\PHPExcel_Writer_Excel5', //\PHPExcel_Writer_Excel2007
            'sheets' => [
                'Report' => [
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
