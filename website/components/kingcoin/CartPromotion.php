<?php
namespace website\components\kingcoin;

use Yii;
use yii\base\Model;
use website\models\Promotion;
use yii2mod\cart\models\CartItemInterface;
use website\models\Order;

class CartPromotion extends Promotion implements CartItemInterface
{
    const SCENARIO_ADD_PROMOTION = 'promotion';
 
    public $user_id;

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
        if ($this->promotion_scenario != self::SCENARIO_BUY_COIN) {
            $this->addError($attribute, 'This voucher code is not valid');
        }
        if ($this->user_id && !$this->canApplyForUser($this->user_id)) {
            $this->addError($attribute, 'This voucher code is not valid for this user');
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