<?php
namespace common\models\promotions;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;

/**
 * Promotion model
 */
class TotalUsingRule extends PromotionRuleAbstract implements PromotionRuleInterface
{
    public $total;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['total'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'total' => 'Số lần sử dụng',
        ];
    }

    public function render($form, $attr, $options = [])
    {
        if (!$this->isSafeAttribute($attr)) return '';
        return $form->field($this, $attr, $options)->textInput();
    }

    public function isValid($params)
    {
        $command = PromotionApply::find()->where(['promotion_id' => $this->promotion_id]);
        return $command->count() < $this->total;
    }
}