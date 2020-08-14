<?php
namespace common\models\promotions;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\User;

class CustomerOnlyRule extends PromotionRuleAbstract implements PromotionRuleInterface
{
    public $object = self::EFFECT_USER;
    public function render($form, $attr, $options = [])
    {
    }

    public function isValid($userId)
    {
        if (!$userId) return false;
        $user = User::findOne($userId);
        return !$user->reseller;
    }
}