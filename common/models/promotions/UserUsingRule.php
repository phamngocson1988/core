<?php
namespace common\models\promotions;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;

/**
 * UserUsingRule model
 */
class UserUsingRule extends PromotionRuleAbstract implements PromotionRuleInterface
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
            'total' => 'Số lần sử dụng cho 1 khách hàng',
        ];
    }

    public function render($form, $attr, $options = [])
    {
        if (!$this->isSafeAttribute($attr)) return '';
        return $form->field($this, $attr, $options)->textInput();
    }

    public function isValid($params)
    {
        $userId = ArrayHelper::getValue($params, 'user_id');
        if (!$userId) return false;
        $command = PromotionApply::find()->where(['promotion_id' => $this->promotion_id, 'user_id' => $userId]);
        return $command->count() < $this->total;
    }
}