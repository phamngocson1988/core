<?php
namespace frontend\components\cart;

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
    /** @car Cart */
    protected $_cart;

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
        if (!$promotion->number_of_use) return;
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
        if ((int)$promotion->number_of_use <= $command->count()) {
            $this->addError($attribute, 'This voucher code has applied before');
        }
    }

    public function getPromotion()
    {
        if (!$this->_promotion) {
            $type = $this->_cart->isModeProduct() ? Promotion::OBJECT_COIN : Promotion::OBJECT_MONEY;
            $this->_promotion = Promotion::findOne(['code' => $this->code, 'object_type' => $type]);
        }
        return $this->_promotion;
    }

    public function setCart($cart)
    {
        $this->_cart = $cart;
        return $this;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return 0;
        return $promotion->calculateDiscount($this->_cart->getSubTotalPrice());
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
        return 'disconut_' . $promotion->id;
    }
}