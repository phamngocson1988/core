<?php
namespace common\models\promotions;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;

/**
 * PromotionCoinBenefit model
 */
class PromotionCoinBenefit extends PromotionBenefitAbstract implements PromotionBenefitInterface
{
    public $value;
    public $type;
    public $max_value;

    const TYPE_FIX = 'fix';
    const TYPE_PERCENT = 'percent';

    protected static function listTypes() 
    {
        return [self::TYPE_FIX => 'Giá trị cụ thể', self::TYPE_PERCENT => 'Tính giá trị theo phần trăm'];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['value', 'type', 'max_value'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'value' => 'Giá trị',
            'type' => 'Cách tính',
            'max_value' => 'Giới hạn khuyến mãi'
        ];
    }

    public function render($form, $attr, $options = [])
    {
        if (!$this->isSafeAttribute($attr)) return '';
        switch ($attr) {
            case 'value':
                return $form->field($this, $attr, $options)->textInput();
            case 'type':
                return $form->field($this, $attr, $options)->dropDownList(self::listTypes());
            case 'max_value':
                return $form->field($this, $attr, $options)->textInput();
            default:
                return '';
        }
    }

    public function calculate($amount)
    {
        $addition = 0;
        if ($this->type == self::TYPE_FIX) {
            $addition = $this->value;
        } elseif ($this->type == self::TYPE_PERCENT) {
            $addition = ceil(($this->value * $amount) / 100);
        }

        return ($this->max_value && $addition) ? min($this->max_value, $addition) : $addition;
    }

    public function apply($amount)
    {
        return $this->calculate($amount);
    }

    /**
     * @var website\components\cart\CartItem
     */
    public function applyCart(&$cart)
    {
        $cart->setPromotionCoin($this->calculate($cart->getSubTotalCoin()));
    }
}