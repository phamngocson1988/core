<?php
namespace frontend\components\cart;

use Yii;
use yii\base\Model;
use frontend\models\Promotion;
use yii2mod\cart\models\CartItemInterface;
use common\models\Order;

class CartPromotion extends Promotion implements CartItemInterface
{
    public function getPrice() : int
    {
        return 10;
    }

    public function getLabel()
    {
        return $this->title;
    }

    public function getUniqueId()
    {
        return 'promotion-' . $this->id;
    }
}