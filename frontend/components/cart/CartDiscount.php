<?php
namespace frontend\components\cart;

use Yii;
use yii\base\Model;
use common\models\Promotion;
use yii2mod\cart\models\CartItemInterface;

class CartDiscount extends Model implements CartItemInterface
{
    public $code;
    /** @var Promotion */
    protected $_promotion;
    public function rules()
    {
        return [
            [['code'], 'trim'],
            ['code', 'validateCode']
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

    public function getPromotion()
    {
        if (!$this->_promotion) {
            $this->_promotion = Promotion::findOne(['code' => $this->code, 'object_type' => Promotion::OBJECT_COIN]);
        }
        return $this->_promotion;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return 0;
        return $promotion->calculateDiscount(Yii::$app->cart->getSubTotalPrice());
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