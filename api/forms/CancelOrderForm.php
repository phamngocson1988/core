<?php
namespace api\forms;

use Yii;
use yii\base\Model;
use api\models\Order;
use api\components\notifications\OrderNotification;

class CancelOrderForm extends Model
{
    public $id;

    private $_order;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOrder'],
        ];
    }

    public function validateOrder($attribute, $params = [])
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->customer_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->isCompletedOrder() 
            || $order->isConfirmedOrder() 
            || $order->isDeletedOrder()
            || $order->isCancelledOrder()
        ) {
            $this->addError($attribute, 'Order cannot be cancelled anymore.');
        }

    }

    public function cancel()
    {
        $order = $this->getOrder();
        $order->request_cancel = 1;
        $order->request_cancel_time = date('Y-m-d H:i:s');
        $order->request_cancel_description = 'Customer sent cancellation request';

        $order->on(Order::EVENT_AFTER_UPDATE, function ($event) {
            $o = $event->sender;
            $o->log(sprintf("Sent cancel request"));
            // Send notification to saler
            $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
            $o->pushNotification(OrderNotification::NOTIFY_SALER_CANCEL_ORDER, $salerTeamIds);

            // Send notification to orderteam
            $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
            $o->pushNotification(OrderNotification::NOTIFY_ORDERTEAM_CANCEL_ORDER, $orderTeamIds);

            // Send notification to supplier
            $supplier = $o->workingSupplier;
            if ($supplier) {
                $o->pushNotification(OrderNotification::NOTIFY_SUPPLIER_CANCEL_ORDER, $supplier->supplier_id);
            }
        });
        return $order->save();
    }


    protected function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}

