<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;

class UpdateOrderStatusProcessing extends Model
{
    public $id;
    
    private $_order;

    public function rules()
    {
        return [
            [['id'], 'required'],
            ['id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isPendingOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $order->status = Order::STATUS_PROCESSING;
        $order->doing_unit = $order->total_unit;
        $order->process_end_time = date('Y-m-d H:i:s');
        $order->process_duration_time = strtotime($order->process_end_time) - strtotime($order->process_start_time);
        if ($order->save()) {
            $customer = $order->customer;
            $settings = Yii::$app->settings;
            $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
            Yii::$app->urlManagerFrontend->setHostInfo('https://kinggems.us');
            Yii::$app->mailer->compose('admin_send_complete_order', [
                'mail' => $this, 
                'order' => $this->getOrder(),
                'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            ])
            ->setTo($customer->email)
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setSubject("[Kinggems][Order #$this->id] Order Completed Notification")
            ->setTextBody("Your order #<?=$this->id;?> has been completed now. Please review it")
            ->send();
            return true;
        }
        return false;
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}
