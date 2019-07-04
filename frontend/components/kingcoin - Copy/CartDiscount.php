<?php
namespace frontend\components\kingcoin;

use Yii;
use yii\base\Model;
use common\models\Promotion;
use yii2mod\cart\models\CartItemInterface;
use common\models\Order;

class CartDiscount extends Model implements CartItemInterface
{
    public $code;
    /** @var Promotion */
    protected $_promotion;

    public function rules()
    {
        return [
            [['code'], 'trim'],
            ['code', 'validateCode'],
            ['code', 'checkNumberUsing']
        ];
    }

    public function validateCode($attribute, $params)
    {
        if (!$this->code) return;
        $promotion = $this->getPromotion();
        if (!$promotion) {
            $this->addError($attribute, 'This voucher code is not exist');
        } elseif (!$promotion->isEnable()) {
            $this->addError($attribute, 'This voucher code is not enabled');
        } elseif (!$promotion->isValid()) {
            $this->addError($attribute, 'This voucher code is expired');
        }
    }

    public function checkNumberUsing($attribute, $params)
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return;
        if (!$promotion->user_using) return;
        $command = Order::find();
        $command->joinWith('discounts');
        $command->where(["<>", "order.status", Order::STATUS_DELETED]);
        $command->andWhere(["order_fee.reference" => $promotion->id]);
        $command->andWhere(["order.customer_id" => Yii::$app->user->id]);
        if ($promotion->from_date) {
            $command->andWhere([">=", "order.created_at", $promotion->from_date]);
        }
        if ($promotion->to_date) {
            $command->andWhere(["<=", "order.created_at", $promotion->to_date]);
        }
        if ((int)$promotion->user_using <= $command->count()) {
            $this->addError($attribute, 'This voucher code has applied before');
        }
    }

    public function getPromotion()
    {
        if (!$this->_promotion) {
            $this->_promotion = Promotion::findOne(['code' => $this->code, 'promotion_scenario' => Promotion::SCENARIO_BUY_COIN]);
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