<?php
namespace backend\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use backend\models\Order;
use backend\models\UserWallet;
use backend\models\UserCommission;

class OrderEventHandler extends Model
{
    public static function beforeDeleteEvent($event) 
    {
        $order = $event->sender; //order
        if (!$order->isPendingOrder() && !$order->isVerifyingOrder()) {
            return false;
        }
        return true;
    }

    public static function afterDeleteEvent($event) 
    {
        $order = $event->sender; //order
        if ($order->isVerifyingOrder()) {
            foreach ($order->comments as $comment) {
                $comment->delete();
            }
            foreach ($order->complains as $complain) {
                $complain->delete();
            }
        }
    }

    public static function sendMailDeleteOrder($event) 
    {
        $order = $event->sender; //order
         // Send mail notification
         $admin = Yii::$app->params['email_admin'];
         $siteName = Yii::$app->name;
         $email = Yii::$app->mailer->compose('cancel_order', [
             'order' => $order
         ])
         ->setTo($order->customer_email)
         ->setFrom([$admin => $siteName])
         ->setSubject("KINGGEMS.US - Confirmed order cancelation $order->id")
         ->setTextBody("Confirmed order cancelation")
         ->send();
    }

    public static function refundOrder($event) 
    {
        $order = $event->sender; //order
        $user = $order->customer;
        $user->topup($order->sub_total_price, $order->id, $description = 'Refund from cancelling order #' . $order->id);
    }

    public static function removeCommission($event) 
    {
        $order = $event->sender; //order
        $commission = UserCommission::findOne(['order_id' => $order->id]);
        if ($commission) $commission->delete();
    }
}