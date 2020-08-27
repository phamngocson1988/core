<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Paygate;
use yii\helpers\ArrayHelper;
use common\models\Currency;

class CreatePaygateForm extends Model
{
    public $name;
    public $content;
    public $logo;
    public $transfer_fee;
    public $transfer_fee_type;
    public $currency;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'currency'], 'required'],
            [['content', 'logo', 'transfer_fee', 'transfer_fee_type', 'status'], 'trim'],
        ];
    }

    public function create()
    {
        $paygate = new Paygate();
        $paygate->name = $this->name;
        $paygate->paygate_type = 'offline';
        $paygate->content = $this->content;
        $paygate->logo = $this->logo;
        $paygate->transfer_fee = $this->transfer_fee;
        $paygate->transfer_fee_type = $this->transfer_fee_type;
        $paygate->currency = $this->currency;
        $paygate->status = $this->status;
        return $paygate->save();
    }

    public function FetchFeeType()
    {
        return [
            'fix' => 'Giảm cố định',
            'percent' => 'Giảm theo phần trăm'
        ];
    }

    public function fetchCurrency()
    {
        $models = Currency::fetchAll();
        return ArrayHelper::map($models, 'name', 'name');
    }

    public function fetchStatus()
    {
        return [
            Paygate::STATUS_ACTIVE => 'Đang hoạt động',
            Paygate::STATUS_INACTIVE => 'Ngưng hoạt động',
        ];
    }

}
