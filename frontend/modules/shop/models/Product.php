<?php
namespace frontend\modules\shop\models;

use common\modules\shop\models\Product as BaseProduct;
use yii2mod\cart\models\CartItemInterface;

class Product extends BaseProduct implements CartItemInterface
{

    public function getPrice($format = false)
    {
        return $this->price;
    }

    public function getLabel()
    {
        return $this->name;
    }

    public function getUniqueId()
    {
        return $this->id;
    }
}