<?php
namespace frontend\events;

use Yii;
use yii\base\Model;
use frontend\models\PromotionApply;
use frontend\models\UserCommission;

class ShoppingEventHandler extends Model
{
    public static function sendNotificationEmail($event) 
    {
        $order = $event->sender;
        $user = $order->customer;
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        if ($adminEmail) {
            $email = Yii::$app->mailer->compose('place_order', ['order' => $order])
            ->setTo($user->email)
            ->setFrom([$adminEmail => Yii::$app->name . ' Administrator'])
            ->setSubject(sprintf("Order confirmation - %s", $order->id))
            ->setTextBody("Thanks for your order")
            ->send();
        }
    }

    public static function applyVoucherForUser($event) 
    {
        $order = $event->sender;
        if ($order->promotion_id) {
            $promotionItem = $cart->getPromotionItem();
            $apply = new PromotionApply();
            $apply->promotion_id = $order->promotion_id;
            $apply->user_id = $order->customer_id;
            $apply->save();
        }
    }

    public static function applyAffiliateProgram($event) 
    {
        $setting = Yii::$app->settings;
        $order = $event->sender;
        $user = $order->customer;
        if (!$user->affiliated_with) return;
        if (!$setting->get('AffiliateProgramForm', 'status')) return;
        
        $totalPrice = $order->total_price;
        $value = $setting->get('AffiliateProgramForm', 'value', 0);
        $type = $setting->get('AffiliateProgramForm', 'type', 'fix');
        $commission = ($type == 'percent') ? ($totalPrice * $value) / 100 : $value;
        // save to affiliate table
        $userAff = new UserCommission();
        $userAff->user_id = $user->affiliated_with;
        $userAff->commission = round($commission, 1);
        $userAff->order_id = $order->id;
        $userAff->member_id = $user->id;
        $userAff->save();
    }
}