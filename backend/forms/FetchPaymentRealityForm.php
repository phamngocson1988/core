<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PaymentReality;
use common\components\helpers\TimeHelper;
use common\components\helpers\StringHelper;
use common\models\Paygate;

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
    public $paygate;

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
            'paygate' => $this->paygate,
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

    public function fetchPaygate()
    {
        $models = Paygate::find()->all();
        return ArrayHelper::map($models, 'identifier', 'name');
    }

    // Export function
    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $fileName = ($fileName) ? $fileName : 'order-list' . date('His') . '.xlsx';
        $columnKeys = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE', 'AF', 'AG'];
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
            'Chênh lệch (Kcoin)',
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
        $header = [
            "A3:{$endColumn}3" => sprintf('Thời gian cập nhật: Từ %s đến %s', $this->start_date, $this->end_date)
        ];
        $footer = [
            // "A$footerRow" => sprintf('Tổng: %s', $command->count()),
            // "G$footerRow" => sprintf('Tổng: %s', number_format($command->sum('order.quantity'), 1)),
        ];
        
        $data = [];
        $now = date('Y-m-d H:i:s');
        $models = $command->all();
        foreach ($models as $model) {
            $object = $model->object;
            $user = $model->user;
            $commitment = $model->commitment;
            $objectName = '--';
            $fee = '';
            $promotionCode = '';
            $discount = '';
            if ($model->object_name == 'wallet') {
                $objectName = 'Kcoin';
                $fee = $object->total_fee;
                $promotionCode = $object->promotion_code;
                $discount = $object->promotion_coin;
            } elseif ($model->object_name == 'order') {
                $objectName = $object ? $object->game_title : 'Không tìm thấy đơn hàng tương ứng';
                $fee = $object->total_fee;
                $promotionCode = $object->promotion_code;
                $discount = $object->total_discount;
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
            $creator = $model->creator;
            $confirmer = $model->confirmer;
            $confirmerName = '';
            if ($model->isClaimed()) {
                $confirmerName = $confirmer ? $confirmer->name : 'System';
            }
            $commitmentNote = '--';
            if ($model->isClaimed()) {
                $commitmentNote = $commitment ? $commitment->note : '';
            }

            $data[] = [
                $model->getId(),
                $model->isClaimed() ? $model->getObjectKey() . ' ' : '--',
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
                $commitment ? $commitment->payment_id . ' ' : '--', // ma tham chieu nguoi gui
                $model->payment_id . ' ',
                $model->payment_note,
                $model->kingcoin,
                $fee,// phi giao dich
                $discount, // khuyen mai
                $promotionCode, // ma khuyen mai
                $user ? $user->getCountryName() : '', //quoc gia
                $model->currency, // tien te
                round($model->total_amount, 1), // thuc nhan (tien te)
                $model->exchange_rate, // tyr gia
                $commitment ? round($commitment->kingcoin, 1) : '--', // can thanh toan
                round($model->kingcoin, 1), //thuc nhan (kcoin)
                $commitment ? round($model->kingcoin - $commitment->kingcoin, 1) : '--', // chenh lech (kcoin)
                $creator->name, //nguoi nhap
                $confirmerName, // nguoi duyet
                $model->getStatusName(), //trang thai
                $commitment ? $commitment->evidence : '', // hoa don nguoi gui
                $model->evidence, // hoa don nguoi nhan
                strlen($commitmentNote) > 25 ? substr($commitmentNote, 0, 25) . '...' : $commitmentNote, //ghi chu duyet don hang
                strlen($model->payment_note) > 25 ? substr($model->payment_note, 0, 25) : $model->payment_note, // ghi chu nhan tien
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
                        "A1" => [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                            ],
                        ],
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
