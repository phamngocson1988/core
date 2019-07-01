<?php
namespace common\models\promotions;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;

/**
 * PromotionUnitBenefit model
 */
class PromotionUnitBenefit extends PromotionBenefitAbstract implements PromotionBenefitInterface
{
    public $value;
    public $type;

    const TYPE_FIX = 'fix';
    const TYPE_PERCENT = 'percent';

    protected static function listTypes() 
    {
        return [self::TYPE_FIX => 'Giá trị cụ thể', self::TYPE_PERCENT => 'Tính giá trị theo phần trăm'];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['value', 'type'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'value' => 'Giá trị',
            'type' => 'Cách tính',
        ];
    }

    public function render($form, $attr, $options = [])
    {
        if (!$this->isSafeAttribute($attr)) return '';
        switch ($attr) {
            case 'value':
                return $form->field($this, $attr, $options)->textInput();
                break;
            case 'type':
                return $form->field($this, $attr, $options)->dropDownList(self::listTypes());
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
        return $addition;
    }

    public function apply($amount)
    {
        return $this->calculate($amount);
    }

    public function applyCart(&$cart)
    {
        $cart->setPromotionUnit($this->calculate($cart->getSubTotalUnit()));
    }
}