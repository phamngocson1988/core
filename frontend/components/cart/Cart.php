<?php

namespace frontend\components\cart;

use Yii;
use yii2mod\cart\models\CartItemInterface;
use frontend\components\cart\CartPromotion;
use frontend\models\Promotion;

/**
 * Class Cart provides basic cart functionality (adding, removing, clearing, listing items). You can extend this class and
 * override it in the application configuration to extend/customize the functionality
 *
 * @package yii2mod\cart
 */
class Cart extends \yii2mod\cart\Cart
{
	// Declare item type
	const ITEM_PROMOTION = '\frontend\components\cart\CartPromotion';

	protected $promotion_coin;
	protected $promotion_unit;

	/** instance of CartItemInterface */
	//==========Item=========
	public function getItem() 
	{
		$items = $this->getItems(static::ITEM_PRODUCT);
		$item = reset($items);
		return $item;
	}

	//========Promotion==========
	public function getPromotionItem()
	{
		$items = $this->getItems(static::ITEM_PROMOTION);
		$item = reset($items);
		return $item;
	}

	public function setPromotionItem($item)
	{
		$this->add($item);
	}

	public function removePromotionItem()
	{
		$item = $this->getPromotionItem();
		if (!$item) return;
		$this->remove($item->getUniqueId());
	}

	public function hasPromotion()
	{
		$item = $this->getPromotionItem();
		return (boolean)$item;
	}

	public function applyPromotion()
	{
		if (!$this->hasPromotion()) return;
		$promotion = $this->getPromotionItem();
		$this->promotion_coin = $promotion->getPrice();
	}

	public function getPromotionUnit()
	{
		return (int)$this->promotion_unit;
	}

	//============= For product ==========
	/**
	 * The total of items only
	 */
	public function getSubTotalPrice()
	{
		$item = $this->getItem();
		return $item->getTotalPrice();
	}

	public function getTotalPrice()
	{
		$subTotal = $this->getSubTotalPrice();
		$sum = $subTotal;
		return $sum;
	}

	public function getSubTotalUnit()
	{
		$item = $this->getItem();
		return $item->getTotalUnit();
	}

	public function getTotalUnit()
	{
		$subTotal = $this->getSubTotalUnit();
		$sum = $subTotal + $this->getPromotionUnit();
		return $sum;
	}
}