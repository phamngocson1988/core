<?php
namespace payment\forms;

use Yii;
use common\models\Order;
use common\models\PaymentTransaction;

class WalletPaymentForm extends \website\forms\WalletPaymentForm
{
    public $remark;
    public $token;

    protected $_order;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['remark', 'token'], 'trim'];
        $rules[] = ['remark', 'required', 'message' => 'Name is required'];
        $rules[] = ['token', 'validatePaymentToken'];
        $rules[] = ['quantity', 'validatePaymentToken', 'when' => function($model) {
            return !!$model->token;
        }];
        return $rules;
    }

    public function validatePaymentToken($attribute, $params) 
    {
        if (!$this->token) return;
        $order = $this->getOrder();
        if (!$order) {
            return $this->addError($attribute, 'Token is invalid');
            
        }
        $data = $order->validatePaymentToken($this->token);
        if (!$data) {
            return $this->addError($attribute, 'Token is invalid');
        }
        if ($data['user_id'] !== Yii::$app->user->id) {
            return $this->addError($attribute, 'Token is invalid');
        }
        if ($data['amount'] < $order->total_price) {
            return $this->addError($attribute, 'Token is invalid');
        }
    }

    public function validateQuantity($attribute, $params) 
    {
        if (!$this->token) return;
        $order = $this->getOrder();
        $data = $order->validatePaymentToken($this->token);
        if ($data['amount'] < $this->quantity) {
            return $this->addError($attribute, 'Quantity is invalid');
        }
    }

    public function getOrder()
    {
        if (!$this->token) return null;
        if (!$this->_order) {
            $this->_order = Order::find()->where(['payment_token' => $this->token])->one();
        }
        return $this->_order;
    }

    public function purchase()
    {
        $transactionId = parent::purchase();
        $trn = PaymentTransaction::findOne($transactionId);
        $trn->remark = $this->remark;
        $trn->save();
        $order = $this->getOrder();
        if ($order) {
            $data = $order->validatePaymentToken($this->token);
            $jobId = Yii::$app->queueManual->push(new \common\queue\PurchaseOrderByWalletJob([
                'order_id' => $order->id,
                'user_id' => $data['user_id']
            ]));
            $trn->addJobHandler($jobId);
        }
        return $transactionId;
    }
}