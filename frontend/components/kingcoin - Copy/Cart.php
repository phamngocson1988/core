<?php

namespace frontend\components\kingcoin;

use Yii;
use yii2mod\cart\models\CartItemInterface;

/**
 * Class Cart provides basic cart functionality (adding, removing, clearing, listing items). You can extend this class and
 * override it in the application configuration to extend/customize the functionality
 *
 * @package yii2mod\cart
 */
class Cart extends \yii2mod\cart\Cart
{
	const ITEM_DISCOUNT = '\frontend\components\kingcoin\CartDiscount';

	/** instance of CartItemInterface */
	// protected $discount;

	public function getItem() 
	{
		$items = $this->getItems(static::ITEM_PRODUCT);
		$item = reset($items);
		return $item;
	}

	public function getDiscountItem()
	{
		// if ($this->discount instanceof CartItemInterface) return $this->discount;
		// return null;
		$items = $this->getItems(static::ITEM_DISCOUNT);
		$item = reset($items);
		return $item;
	}

	public function setDiscountItem($item)
	{
		$this->add($item);
	}

	public function removeDiscountItem()
	{
		$item = $this->getDiscountItem();
		if (!$item) return;
		$this->remove($item->getUniqueId());
	}

	public function hasDiscount()
	{
		// return (boolean)$this->discount;
		$item = $this->getDiscountItem();
		return (boolean)$item;
	}

	/**
	 * The total of products only
	 */
	public function getSubTotalPrice()
	{
		$item = $this->getItem();
		return $item->getTotalPrice();
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
		if (!$this->hasDiscount()) return 0;
		$discount = $this->getDiscountItem();
		return $discount->getPrice();
	}
}