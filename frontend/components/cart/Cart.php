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
	const ITEM_PRICING = '\frontend\components\cart\CartPricingItem';
	const ITEM_DISCOUNT = '\frontend\components\cart\CartDiscount';

	private $mode;

	public function setMode($type)
	{
		switch ($type) {
			case 'pricing': 
				$this->mode = self::ITEM_PRICING;
				break;
			case 'product':
				$this->mode = self::ITEM_PRODUCT;
				break;
			default:
				die('Not support');
				break;
		}
		return $this;
	}

	public function isModeProduct()
	{
		return $this->mode == self::ITEM_PRODUCT;
	}

	public function isModePricing()
	{
		return $this->mode == self::ITEM_PRICING;
	}

	// public function getItemType($type)
	// {
	// 	switch ($type) {
	// 		case 'discount':
	// 			return self::ITEM_DISCOUNT;
	// 		case 'pricing': 
	// 			return self::ITEM_PRICING;
	// 		case 'product':
	// 			return self::ITEM_PRODUCT;
	// 		default:
	// 			return self::ITEM_PRODUCT;
	// 	}
	// }

	public function getItem() 
	{
		if (!$this->mode) die('You have not set mode for cart');
		$items = $this->getItems($this->mode);
		$item = reset($items);
		return $item;
	}

	// public function getItems($mode = null): array
	// {
	// 	// if ($mode) $this->setMode($mode);
	// 	if (!$this->mode && !$mode) die('You have not set mode for cart');
	// 	$mode = ($mode) ? $mode : $this->mode;
	// 	return parent::getItems($mode);
	// }

	
	/**
	 * The total of products only
	 */
	public function getSubTotalPrice()
	{
		if (!$this->mode) die('You have not set mode for cart');
		$items = $this->getItems($this->mode);
		$sum = 0;
		foreach ($items as $item) {
			$sum += $item->getTotalPrice();
		}
		return $sum;
	}

	public function getTotalPrice()
	{
		if (!$this->mode) die('You have not set mode for cart');
		$subTotal = $this->getSubTotalPrice($this->mode);
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
		$items = $this->getDiscounts();
		$sum = 0;
		foreach ($items as $item) {
			$item->setCart($this);
			$sum += $item->getPrice();
		}
		return $sum;
	}

	public function getDiscount() 
	{
		$items = $this->getDiscounts();
		$item = reset($items);
		return $item;
	}

	public function getDiscounts()
	{
		$items = $this->getItems(self::ITEM_DISCOUNT);
		return $items;
	}

}