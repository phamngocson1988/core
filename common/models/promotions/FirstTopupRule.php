<?php
namespace common\models\promotions;

use Yii;
use common\models\PaymentTransaction;

/**
 * SpecifiedGamesRule model
 */
class FirstTopupRule extends PromotionRuleAbstract implements PromotionRuleInterface
{
    public function render($form, $attr, $options = [])
    {
        
    }

    public function isValid($userId)
    {
        return PaymentTransaction::find()->where([
            'user_id' => $userId,
            'status' => PaymentTransaction::STATUS_COMPLETED
        ])->count == 1;
    }
}