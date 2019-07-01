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
        if (!$this->isValid()) {
            $this->addError($attribute, 'This voucher code is not valid');
        }
    }

    //===========================

    public function getPrice() : int
    {
        return 10;
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