<?php
namespace frontend\components\cart;

use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use common\models\PricingCoin;

class CartPricingItem extends Model implements CartItemInterface
{
    public $pricing_id;
    public $quantity;

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';

    /** @var PricingCoin **/
    protected $_pricing;

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['pricing_id', 'quantity'],
            self::SCENARIO_EDIT => ['quantity'],
        ];
    }

    public function rules()
    {
        return [
            [['pricing_id', 'quantity'], 'required', 'on' => self::SCENARIO_ADD],
            [['pricing_id', 'quantity'], 'required', 'on' => self::SCENARIO_EDIT],
            ['pricing_id', 'validatePricing'],
        ];
    }

    public function validatePricing($attribute, $params)
    {
        $pricing = $this->getPricing();
        if (!$pricing) {
            $this->addError($attribute, 'Không tìm thấy gói sản phẩm');
        }
    }

    public function getPricing()
    {
        if (!$this->_pricing) {
            $this->_pricing = PricingCoin::findOne($this->pricing_id);
        }
        return $this->_pricing;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        $pricing = $this->getPricing();
        if (!$pricing) return 0;
        return (int)$pricing->amount;
    }

    public function getLabel()
    {
        $pricing = $this->getPricing();
        if (!$pricing) return '';
        return $pricing->title;
    }

    public function getUniqueId()
    {
        return 'pricing_' . $this->pricing_id;
    }
}