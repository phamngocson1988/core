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
	public function getTotalPrice()
	{
		$items = $this->getItems();
		$sum = 0;
		foreach ($items as $item) {
			$sum += $item->getTotalPrice();
		}
		return $sum;
	}
}