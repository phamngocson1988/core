<?php

namespace website\components\cart;

use Yii;
use website\models\Promotion;

/**
 * Class Cart provides basic cart functionality (adding, removing, clearing, listing items). You can extend this class and
 * override it in the application configuration to extend/customize the functionality
 *
 * @package yii2mod\cart
 */
class Cart extends \yii2mod\cart\Cart
{
	public function getItem()
    {
        $items = $this->getItems(static::ITEM_PRODUCT);
        return reset($items);
    }

    public function getSubTotalPrice()
    {
        $item = $this->getItem();
        return $item->getSubTotalPrice();
    }

    public function getPromotionDiscount()
    {
        $item = $this->getItem();
        return $item->getPromotionDiscount();
    }

    public function getTotalPrice()
    {
        $item = $this->getItem();
        return $item->getTotalPrice();
    }
}