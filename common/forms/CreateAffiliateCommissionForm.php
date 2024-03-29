<?php
namespace common\forms;

use Yii;
use common\models\AffiliateCommission;
use common\models\Order;
use common\models\User;

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
        if (!$order->isCompletedOrder()) {
            return $this->addError($attribute, 'Order is not valid');
        }
        $affiliateStatus = Yii::$app->settings->get('AffiliateProgramForm', 'status', 0);
        if (!$affiliateStatus) {
            return $this->addError($attribute, 'Affiliate program is disabled');
        }

        // find affiliate
        $user = $order->customer;
        if (!$user->affiliated_with) {
            return $this->addError($attribute, 'The order does not belong to affiliate');
        }

        $affiliateMinMember = Yii::$app->settings->get('AffiliateProgramForm', 'min_member', 0);
        $countAffiliate = User::find()->where(['affiliated_with' => $user->affiliated_with])->count();
        if ($affiliateMinMember && $countAffiliate < $affiliateMinMember) {
            return $this->addError($attribute, "User need to have at least $affiliateMinMember to start affiliate program");
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
        $regularCommissionValue = Yii::$app->settings->get('AffiliateProgramForm', 'value', 0);
        $resellerCommissionValue = Yii::$app->settings->get('AffiliateProgramForm', 'reseller_value', 0);
        $affiliateCommissionDuration = Yii::$app->settings->get('AffiliateProgramForm', 'duration', 0);

        // If affiliate commission type is percent, apply regular commission value
        // If affiliate commission type is fix, apply commission value for corresponding user type (reseller or non-reseller)
        $commissionValue = 0;
        if ($affiliateType === "percent") {
            $commissionValue = ($totalPrice * $regularCommissionValue) / 100;
        } else {
            $commissionValue = $user->isReseller() ? $resellerCommissionValue * $order->quantity : $regularCommissionValue * $order->quantity;
        }
        $commssion = new AffiliateCommission([
            'user_id' => $user->affiliated_with,
            'commission' => $commissionValue,
            'order_id' => $order->id,
            'customer_name' => $order->customer_name,
            'game_title' => $order->game_title,
            'member_id' => $user->id,
            'description' => sprintf('Affiliate commssion from %s (%s)', $user->getName(), $user->id),
            'created_at' => $now,
            'valid_from_date' => date('Y-m-d H:i:s', strtotime(sprintf("+%s days", $affiliateCommissionDuration))),
            'status' => AffiliateCommission::STATUS_VALID
        ]);
        return $commssion->save();
    }
}