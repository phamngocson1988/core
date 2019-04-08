<?php

namespace frontend\components\cart;

use Yii;

/**
 * Class Cart provides basic cart functionality (adding, removing, clearing, listing items). You can extend this class and
 * override it in the application configuration to extend/customize the functionality
 *
 * @package yii2mod\cart
 */
class Cart extends \yii2mod\cart\Cart
{
	const ITEM_PRODUCT = '\frontend\components\cart\CartItem';
	const ITEM_DISCOUNT = '\frontend\components\cart\CartDiscount';

	public function getItemType($type)
	{
		switch ($type) {
			case 'discount':
				return self::ITEM_DISCOUNT;
			default:
				return self::ITEM_PRODUCT;
		}
	}

	public function getItem($type = null) 
	{
		$items = $this->getItems($type);
		$item = reset($items);
		return $item;
	}

	/**
	 * The total of products only
	 */
	public function getSubTotalPrice()
	{
		$items = $this->getItems(self::ITEM_PRODUCT);
		$sum = 0;
		foreach ($items as $item) {
			$sum += $item->getTotalPrice();
		}
		return $sum;
	}

	public function getTotalPrice()
	{
		$subTotal = $this->getSubTotalPrice();
		$fee = $this->getTotalFee();
		$discount = $this->getTotalDiscount();
		$sum = $subTotal + $fee - $discount;
		return $sum;
	}

	public function getTotalFee()
	{
		return 0;
	}

	public function getTotalDiscount()
	{
		$items = $this->getItems(self::ITEM_DISCOUNT);
		$sum = 0;
		foreach ($items as $item) {
			$sum += $item->getPrice();
		}
		return $sum;
	}
}