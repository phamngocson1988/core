<?php

namespace backend\forms;

use Yii;
use common\models\PaymentReality;
use yii\helpers\ArrayHelper;
use common\models\CurrencySetting;
use common\models\Paygate;

class CreatePaymentRealityForm extends ActionForm
{
    public $paygate;
    public $payer;
    public $payment_time;
    public $payment_id;
    public $payment_note;
    public $total_amount;
    public $currency;
    public $note;
    public $file_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $currencyList = $this->fetchCurrency();
        $currencyCodes = array_keys($currencyList);
        return [
            [['paygate', 'payer', 'payment_id', 'payment_note', 'total_amount', 'currency', 'note'], 'trim'],
            [['paygate', 'payer', 'payment_time', 'payment_id', 'total_amount', 'currency'], 'required'],
            ['currency', 'in', 'range' => $currencyCodes],
            ['payment_id', 'unique', 'targetClass' => PaymentReality::className(), 'message' => 'Mã nhận tiền này đã được sử dụng'],
        ];
    }

    public function create()
    {
        if (!$this->validate()) return false;
        $currency = $this->getCurrency();
        $exchange_rate = $currency ? $currency->exchange_rate : 0;
        $kingcoin = $exchange_rate ? round($this->total_amount / $exchange_rate, 2) : 0;
        $payment = new PaymentReality();
        $payment->paygate = $this->paygate;
        $payment->payer = $this->payer;
        $payment->payment_time = $this->payment_time;
        $payment->payment_id = $this->payment_id;
        $payment->payment_note = $this->payment_note;
        $payment->exchange_rate = $exchange_rate;
        $payment->kingcoin = $kingcoin;
        $payment->total_amount = $this->total_amount;
        $payment->currency = $this->currency;
        $payment->note = $this->note;
        $payment->status = PaymentReality::STATUS_PENDING;
        $payment->payment_type = PaymentReality::PAYMENTTYPE_OFFLINE;
        return $payment->save();
    }

    public function fetchCurrency()
    {
        $models = CurrencySetting::find()->all();
        return ArrayHelper::map($models, 'code', 'name');
    }

    public function getCurrency()
    {
        return CurrencySetting::findOne(['code' => $this->currency]);
    }

    public function fetchPaygate()
    {
        $models = Paygate::find()->all();
        return ArrayHelper::map($models, 'identifier', 'name');
    }
}
