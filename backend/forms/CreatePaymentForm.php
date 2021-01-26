<?php

namespace backend\forms;

use Yii;
use common\models\Payment;
use yii\helpers\ArrayHelper;
use common\models\CurrencySetting;
use common\models\Paygate;

class CreatePaymentForm extends ActionForm
{
    public $paygate;
    public $payer;
    public $payment_time;
    public $payment_id;
    public $payment_note;
    public $amount;
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
            [['paygate', 'payer', 'payment_id', 'payment_note', 'amount', 'currency', 'note'], 'trim'],
            [['paygate', 'payer', 'payment_time', 'payment_id', 'amount', 'currency'], 'required'],
            ['currency', 'in', 'range' => $currencyCodes],
            ['payment_id', 'unique', 'targetClass' => Payment::className(), 'message' => 'Mã nhận tiền này đã được sử dụng'],
        ];
    }

    public function create()
    {
        $currency = $this->getCurrency();
        $exchange_rate = $currency ? $currency->exchange_rate : 0;
        $kingcoin = $exchange_rate ? round($this->amount / $exchange_rate, 2) : 0;
        $payment = new Payment();
        $payment->paygate = $this->paygate;
        $payment->payer = $this->payer;
        $payment->payment_time = $this->payment_time;
        $payment->payment_id = $this->payment_id;
        $payment->payment_note = $this->payment_note;
        $payment->exchange_rate = $exchange_rate;
        $payment->kingcoin = $kingcoin;
        $payment->amount = $this->amount;
        $payment->currency = $this->currency;
        $payment->note = $this->note;
        $payment->status = Payment::STATUS_PENDING;
        $payment->payment_type = Payment::PAYMENTTYPE_OFFLINE;
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
