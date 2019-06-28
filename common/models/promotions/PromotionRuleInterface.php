<?php
namespace common\models\promotions;

use Yii;

/**
 * PromotionBenefitAbstract model
 */
interface PromotionRuleInterface
{
    public function render($form, $attr, $options = []);

    public function isValid($params);
}