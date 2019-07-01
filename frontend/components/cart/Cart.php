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
	const ITEM_PROMOTION = 'CartPromotion';

	// Declare scenario
	const SCENARIO_ADD_PROMOTION = 'promotion';

	public $promotion_code;

	public $promotion_coin;
	public $promotion_unit;

	//========== Validate promotion============
	public function scenarios()
    {
        return [
            self::SCENARIO_ADD_PROMOTION => ['promotion_code'],
        ];
    }

	public function rules()
    {

        return [
            [['promotion_code'], 'trim'],
            ['promotion_code', 'validateCode'],
        ];
    }

    public function validateCode($attribute, $params)
    {
        if (!$this->promotion_code) return;
        $user_id = Yii::$app->user->id;
        $item = $this->getItem();
        $game_id = $item->id;
        $promotion = $this->getPromotion();
        if (!$promotion) {
            $this->addError($attribute, 'This voucher code is not exist');
        } elseif (!$promotion->isValid(['user_id' => $user_id, 'game_id' => $game_id])) {
            $this->addError($attribute, 'This voucher is not valid');
        }
    }

    public function getPromotion()
    {
        return CartPromotion::findOne(['code' => $this->promotion_code, 'promotion_scenario' => Promotion::SCENARIO_BUY_GEMS]);
    }



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
		$this->promotion_unit = $promotion->getPrice();
		$this->promotion_coin = $promotion->getPrice();
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
		if (!$this->promotion_coin) $this->applyPromotion();
		$subTotal = $this->getSubTotalPrice();
		$sum = $subTotal - $this->promotion_coin;
		return $sum;
	}

	public function getSubTotalUnit()
	{
		$item = $this->getItem();
		return $item->getTotalUnit();
	}

	public function getTotalUnit()
	{
		if (!$this->promotion_unit) $this->applyPromotion();
		$subTotal = $this->getSubTotalUnit();
		$sum = $subTotal - $this->promotion_unit;
		return $sum;
	}
}