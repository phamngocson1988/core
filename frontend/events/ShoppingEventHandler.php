<?php
namespace frontend\events;

use Yii;
use yii\base\Model;
use frontend\models\PromotionApply;
use frontend\models\UserAffiliate;

class ShoppingEventHandler extends Model
{
    public static function sendNotificationEmail($event) 
    {
        $form = $event->sender;
        $user = $form->user;
        $order = $form->getOrder();
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
        $form = $event->sender;
        $cart = $form->cart;
        $user = $form->user;
        if ($cart->hasPromotion()) {
            $promotionItem = $cart->getPromotionItem();
            $apply = new PromotionApply();
            $apply->promotion_id = $promotionItem->id;
            $apply->user_id = $user->id;
            $apply->save();
        }
    }

    public static function applyAffiliateProgram($event) 
    {
        $setting = Yii::$app->settings;
        $form = $event->sender;
        $cart = $form->cart;
        $user = $form->user;
        $order = $form->getOrder();
        if (!$user->affiliated_with) return;
        if (!$setting->get('AffiliateProgramForm', 'status')) return;
        if ($cart->hasPromotion()) $cart->applyPromotion();
        $totalPrice = $cart->getTotalPrice();
        $value = $setting->get('AffiliateProgramForm', 'value', 0);
        $type = $setting->get('AffiliateProgramForm', 'type', 'fix');
        $commission = ($type == 'percent') ? ($totalPrice * $value) / 100 : $value;
        // save to affiliate table
        $userAff = new UserAffiliate();
        $userAff->user_id = $user->id;
        $userAff->commission = $commission;
        $userAff->order_id = $order->id;
        $userAff->save();
    }
}