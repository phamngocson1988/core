<?php
namespace frontend\components\cart;

use Yii;
use yii\base\Model;
use frontend\models\Promotion;
use yii2mod\cart\models\CartItemInterface;
use common\models\Order;

class CartDiscount extends Model #implements CartItemInterface
{
    // ============== implement interface ===========//
    public function getPrice() : int
    {
        $promotion = $this->getPromotion();
        $cart = Yii::$app->cart;
        if (!$promotion) return 0;
        return $promotion->calculateDiscount($cart->getSubTotalPrice());
    }

    public function getLabel()
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return '';
        return $promotion->title;
    }

    public function getUniqueId()
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return '';
        return 'discount' . $promotion->id;
    }
}