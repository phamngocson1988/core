<?php
namespace frontend\modules\shop\models;

use common\modules\shop\models\Product as BaseProduct;
use yii2mod\cart\models\CartItemInterface;
use yii\helpers\Url;

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

    public function getReadUrl($scheme = false)
    {
        return Url::to(["/shop/product/index", 'id' => $this->id, 'slug' => $this->slug], $scheme);
    }
}