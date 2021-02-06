<?php

namespace backend\forms;

use Yii;
use common\models\PaymentReality;
use common\models\PaymentCommitment;
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
    public $evidence;

    public $autoApproveTransaction = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $currencyList = $this->fetchCurrency();
        $currencyCodes = array_keys($currencyList);
        return [
            [['paygate', 'payer', 'payment_id', 'payment_note', 'total_amount', 'currency', 'note', 'evidence'], 'trim'],
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
        $payment->evidence = $this->evidence;

        // Approve transaction automatically
        if ($this->autoApproveTransaction) {
            $payment->on(PaymentReality::EVENT_AFTER_INSERT, function($event) {
                $model = $event->sender; //PaymentReality
                if (!$model->payment_id) return;
                $commitment = PaymentCommitment::find()->where([
                    'payment_id' => $model->payment_id,
                    'status' => PaymentCommitment::STATUS_PENDING,
                ])->one();
                if (!$commitment) return;
                $approveTransactionService = new ApprovePaymentCommitmentForm([
                    'id' => $commitment->id,
                    'payment_reality_id' => $model->id,
                    'note' => sprintf('Transaction is approved automatically, after creating %s', $model->getId()),
                    'confirmed_by' => $model->created_by,
                ]);
                $approveTransactionService->setReality($model);
                $approveTransactionService->setCommitment($commitment);
                $approveTransactionService->approve();
            });
        }
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
