<?php
namespace frontend\components\cart;

use Yii;
use yii\base\Model;
use frontend\models\Promotion;
use yii2mod\cart\models\CartItemInterface;
use common\models\Order;

class CartPromotion extends Model implements CartItemInterface
{
    public $code;
    public $user_id;
    public $game_ids = [];
    /** @var Promotion */
    protected $_promotion;

    public function rules()
    {
        return [
            [['code'], 'trim'],
            ['code', 'validateCode'],
            // ['code', 'checkValidGame'],
            // ['code', 'checkValidUser'],
            // ['code', 'checkUserUsing'],
            // ['code', 'checkTotalUsing']
        ];
    }

    public function validateCode($attribute, $params)
    {
        if (!$this->code) return;
        $promotion = $this->getPromotion();
        if (!$promotion) {
            $this->addError($attribute, 'This voucher code is not exist');
        // } elseif (!$promotion->isEnable()) {
        //     $this->addError($attribute, 'This voucher code is not enabled');
        // } elseif (!$promotion->isValid()) {
        //     $this->addError($attribute, 'This voucher code is expired');
        } elseif (!$promotion->isValid(['user_id' => $this->user_id, 'game_id' => $this->game_ids])) {
            $this->addError($attribute, 'This voucher is not valid');
        }
    }

    // public function checkValidGame($attribute, $params) 
    // {
    //     if ($this->hasError()) return;
    //     if (empty($this->game_ids)) return;
    //     $promotion = $this->getPromotion();
    //     foreach ($this->game_ids as $gameId) {
    //         if ($promotion->canApplyGame($gameId)) return true;
    //     }
    //     $this->addError($attribute, "This voucher is not applied for these games");
    // }

    // public function checkValidUser($attribute, $params) 
    // {
    //     if ($this->hasError()) return;
    //     if (!$this->user_id) return;
    //     $promotion = $this->getPromotion();
    //     if (!$promotion->canApplyUser($this->user_id)) {
    //         $this->addError($attribute, 'This voucher is not applied for you');
    //     }
    // }

    // public function checkUserUsing($attribute, $params)
    // {
    //     if ($this->hasError()) return;
    //     if (!$this->user_id) return;
    //     $promotion = $this->getPromotion();
    //     if (!$promotion) return;
    //     if (!$promotion->user_using) return;
    //     $command = Order::find();
    //     $command->joinWith('promotions');
    //     $command->where(["order_fee.reference" => $promotion->id]);
    //     $command->andWhere(["order.customer_id" => $this->user_id]);
    //     if ((int)$promotion->user_using <= $command->count()) {
    //         $this->addError($attribute, 'This voucher code has applied before');
    //     }
    // }

    // public function checkTotalUsing($attribute, $params)
    // {
    //     if ($this->hasError()) return;
    //     $promotion = $this->getPromotion();
    //     if (!$promotion) return;
    //     if (!$promotion->total_using) return;
    //     $command = Order::find();
    //     $command->joinWith('promotions');
    //     $command->where(["order_fee.reference" => $promotion->id]);
    //     if ((int)$promotion->total_using <= $command->count()) {
    //         $this->addError($attribute, 'This voucher code has been used by others');
    //     }
    // }

    public function getPromotion()
    {
        if (!$this->_promotion) {
            $this->_promotion = Promotion::findOne(['code' => $this->code, 'promotion_scenario' => Promotion::SCENARIO_BUY_GEMS]);
        }
        return $this->_promotion;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        $promotion = $this->getPromotion();
        $cart = Yii::$app->cart;
        if (!$promotion) return 0;
        return $promotion->calculateDiscount($cart->getSubTotalPrice());
    }

    public function getLabel()
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return '';
        return $promotion->title;
    }

    public function getUniqueId()
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return '';
        return 'discount' . $promotion->id;
    }
}