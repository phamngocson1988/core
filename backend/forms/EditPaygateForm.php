<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Paygate;
use yii\helpers\ArrayHelper;
use common\models\Currency;

class EditPaygateForm extends Model
{
    public $id;
    public $name;
    public $content;
    public $logo;
    public $transfer_fee;
    public $transfer_fee_type;
    public $currency;
    public $status;

    protected $_paygate;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'currency'], 'required'],
            ['id', 'validatePaygate'],
            [['content', 'logo', 'transfer_fee', 'transfer_fee_type', 'status'], 'trim'],
        ];
    }

    public function validatePaygate($attribute, $pararms = []) 
    {
      $paygate = $this->getPaygate();
      if (!$paygate) {
        $this->addError($attribute, 'Cổng thanh toán không tồn tại');
      }
    }

    public function update()
    {
        $paygate = $this->getPaygate();
        $paygate->name = $this->name;
        $paygate->content = $this->content;
        $paygate->logo = $this->logo;
        $paygate->transfer_fee = $this->transfer_fee;
        $paygate->transfer_fee_type = $this->transfer_fee_type;
        $paygate->currency = $this->currency;
        $paygate->status = $this->status;
        return $paygate->save();
    }

    public function getPaygate()
    {
        if (!$this->_paygate) {
            $this->_paygate = Paygate::findOne($this->id);
        }
        return $this->_paygate;
    }

    public function loadData()
    {
        $paygate = $this->getPaygate();
        $this->name = $paygate->name;
        $this->content = $paygate->content;
        $this->logo = $paygate->logo;
        $this->transfer_fee = $paygate->transfer_fee;
        $this->transfer_fee_type = $paygate->transfer_fee_type;
        $this->currency = $paygate->currency;
        $this->status = $paygate->status;
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
