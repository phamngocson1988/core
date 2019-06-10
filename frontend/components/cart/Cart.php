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
	public function getItem() 
	{
		$items = $this->getItems();
		$item = reset($items);
		return $item;
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
		return 0;
	}
}