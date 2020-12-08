<?php

namespace api\components\cart;

use Yii;

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
        return $item->getTotalPrice();
    }

    public function getTotalPrice()
    {
        $item = $this->getItem();
        return $item->getTotalPrice();
    }
}