<?php
namespace frontend\components\cart;

use Yii;
use yii\base\Model;
use frontend\models\Promotion;
use yii2mod\cart\models\CartItemInterface;
use common\models\Order;

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
        if ($this->user_id && !$this->canApplyForUser($this->user_id)) {
            $this->addError($attribute, 'This voucher code is not valid for this user');
        }
        if ($this->game_id && !$this->canApplyForGame($this->game_id)) {
            $this->addError($attribute, 'This voucher code is not valid for this game');
        }
    }

   
    //===========================

    public function getPrice() : int
    {
        return $this->get;
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