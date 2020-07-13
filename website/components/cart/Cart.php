<?php

namespace website\components\cart;

use Yii;

/**
 * Class Cart provides basic cart functionality (adding, removing, clearing, listing items). You can extend this class and
 * override it in the application configuration to extend/customize the functionality
 *
 * @package yii2mod\cart
 */
class Cart extends \yii2mod\cart\Cart
{
	public function getItem($itemType = null)
    {
        $items = $this->getItems($itemType);
        return reset($items);
    }

    public function getSubTotalPrice()
    {
        $item = $this->getItem();
        return $item->getTotalPrice();
    }

    public function getTotalPrice()
    {
        $item = $this->getItem();
        return $item->getTotalPrice();
    }
}