<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Paygate;
use yii\helpers\ArrayHelper;
use common\models\CurrencySetting;

class CreatePaygateForm extends Model
{
    public $name;
    public $content;
    public $logo;
    public $paygate_type;
    public $transfer_fee;
    public $transfer_fee_type;
    public $currency;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $types = $this->fetchPaygateTypes();
        return [
            [['name', 'currency', 'paygate_type'], 'required'],
            [['content', 'logo', 'transfer_fee', 'transfer_fee_type', 'status'], 'trim'],
            ['paygate_type', 'in', 'range' => array_keys($types)],
        ];
    }

    public function create()
    {
        $paygate = new Paygate();
        $paygate->name = $this->name;
        $paygate->paygate_type = $this->paygate_type;
        $paygate->content = $this->content;
        $paygate->logo = $this->logo;
        $paygate->transfer_fee = $this->transfer_fee;
        $paygate->transfer_fee_type = $this->transfer_fee_type;
        $paygate->currency = $this->currency;
        $paygate->status = $this->status;
        return $paygate->save();
    }

    public function fetchFeeType()
    {
        return [
            'fix' => 'Giảm cố định',
            'percent' => 'Giảm theo phần trăm'
        ];
    }

    public function fetchCurrency()
    {
        $models = CurrencySetting::find()->all();
        return ArrayHelper::map($models, 'code', 'name');
    }

    public function fetchStatus()
    {
        return [
            Paygate::STATUS_ACTIVE => 'Đang hoạt động',
            Paygate::STATUS_INACTIVE => 'Ngưng hoạt động',
        ];
    }

    public function fetchPaygateTypes()
    {
        return [
            Paygate::PAYGATE_TYPE_OFFLINE => 'Offline',
            Paygate::PAYGATE_TYPE_ONLINE => 'Online',
        ];
    }

}
