<?php
namespace common\forms;

use Yii;
use common\models\AffiliateCommission;
use common\models\Order;

class CreateAffiliateCommissionForm extends ActionForm
{
    public $order_id; // order

    protected $_order;

    public function rules()
    {
        return [
            [['order_id'], 'trim'],
            [['order_id'], 'required'],
            ['order_id', 'validateOrder'],
        ];
    }

    public function validateOrder($attribute) 
    {
        $order = $this->getOrder();
        if (!$order) {
            return $this->addError($attribute, 'Order is not exist');
        }
        if (!$order->isPendingOrder()) {
            return $this->addError($attribute, 'Order is not valid');
        }

        // find affiliate
        $user = $order->customer;
        if (!$user->affiliated_by) {
            return $this->addError($attribute, 'The order does not belong to affiliate');
        }
    }

    public function setOrder($order) 
    {
        $this->_order = $order;
    }

    public function getOrder() 
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->order_id);
        }
        return $this->_order;
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $user = $order->customer;
        $now = date('Y-m-d H:i:s');
        $totalPrice = $order->price * $order->quantity;
        $affiliateType = Yii::$app->settings->get('AffiliateProgramForm', 'type', 'fix');
        $affiliateValue = Yii::$app->settings->get('AffiliateProgramForm', 'value', 0);
        $commissionValue = $affiliateType === "percent" ? $totalPrice * $affiliateValue : $affiliateValue;
        $commssion = new AffiliateCommission([
            'user_id' => $user->affiliated_by,
            'commission' => $commissionValue,
            'order_id' => $order->id,
            'member_id' => $user->id,
            'description' => 'Affiliate commssion from ' . $user->getName(),
            'created_at' => $now,
            'valid_from_date' => date(strtotime("+" . Yii::$app->settings->get('AffiliateProgramForm', 'duration', 30) . ' days')),
            'valid_to_date' => $now,
            'status' => AffiliateCommission::STATUS_VALID
        // $duration = Yii::$app->settings->get('AffiliateProgramForm', 'duration', 30);
        ]);
        return $commssion->save();
    }
}