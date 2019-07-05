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
	const ITEM_PROMOTION = '\frontend\components\kingcoin\CartPromotion';

	protected $promotion_coin;
	protected $promotion_unit;
	protected $promotion_money;

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
		// if ($this->Promotion instanceof CartItemInterface) return $this->Promotion;
		// return null;
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
		// return (boolean)$this->Promotion;
		$item = $this->getPromotionItem();
		return (boolean)$item;
	}

	public function applyPromotion()
	{
		if (!$this->hasPromotion()) return;
		$promotion = $this->getPromotionItem();
		$promotion->applyCart($this);
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

	public function getSubTotalCoin()
	{
		$item = $this->getItem();
		return $item->getTotalCoin();
	}

	public function getTotalCoin()
	{
		$subTotal = $this->getSubTotalCoin();
		$sum = $subTotal + $this->getPromotionCoin();
		return $sum;
	}

	// =============get/set benefit===========
	public function getPromotionUnit()
	{
		return (int)$this->promotion_unit;
	}

	public function setPromotionUnit($amount)
	{
		$this->promotion_unit = $amount;
	}

	public function getPromotionCoin()
	{
		return (int)$this->promotion_coin;
	}

	public function setPromotionCoin($amount)
	{
		$this->promotion_coin = $amount;
	}

	public function getPromotionMoney()
	{
		return (int)$this->promotion_money;
	}

	public function setPromotionMoney($amount)
	{
		$this->promotion_money = $amount;
	}


}