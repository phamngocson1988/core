<?php
namespace common\models\promotions;

use Yii;
use common\models\PaymentTransaction;
use common\models\PromotionApply;

/**
 * SpecifiedGamesRule model
 */
class FirstTopupRule extends PromotionRuleAbstract implements PromotionRuleInterface
{
    public $object = self::EFFECT_USER;
    public function render($form, $attr, $options = [])
    {
        
    }

    public function isValid($userId)
    {
        return PaymentTransaction::find()->where([
            'user_id' => $userId,
            'status' => PaymentTransaction::STATUS_COMPLETED,
            'promotion_id' => $this->promotion_id
        ])->count() == 0;
    }
}