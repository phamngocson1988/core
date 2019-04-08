<?php
namespace frontend\components\cart;

use common\models\Promotion;
use yii2mod\cart\models\CartItemInterface;

class CartDiscount extends Promotion implements CartItemInterface
{
    // ============== implement interface ===========//
    public function getPrice() : int
    {
        return $this->value;
    }

    public function getLabel()
    {
        return $this->title;
    }

    public function getUniqueId()
    {
        return $this->id;
    }
}