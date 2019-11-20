<?php
namespace reseller\components\cart;

use Yii;
use yii\base\Model;
use reseller\models\Promotion;
use yii2mod\cart\models\CartItemInterface;
use reseller\models\Order;

class CartPromotion extends Promotion implements CartItemInterface
{
    const SCENARIO_ADD_PROMOTION = 'promotion';
 
    public $user_id;
    public $game_id;

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_PROMOTION => ['code'],
        ];
    }

	public function rules()
    {
        return [
            [['code'], 'trim'],
            ['code', 'validateCode'],
        ];
    }

    public function validateCode($attribute, $params)
    {
        if ($this->promotion_scenario != self::SCENARIO_BUY_GEMS) {
            $this->addError($attribute, 'This voucher code is not valid');
        }
        if ($this->user_id && !$this->canApplyForUser($this->user_id)) {
            $this->addError($attribute, 'This voucher code is not valid for this user');
        }
        if ($this->game_id && !$this->canApplyForGame($this->game_id)) {
            $this->addError($attribute, 'This voucher code is not valid for this game');
        }
    }

    public function applyCart(&$cart)
    {
        $benefit = $this->getBenefit();
        if (!$benefit) return;
        $benefit->applyCart($cart);
    }

   
    //===========================

    public function getPrice() : int
    {
        return 0;
    }

    public function getLabel()
    {
        return $this->title;
    }

    public function getUniqueId()
    {
        return 'promotion-' . $this->id;
    }
}