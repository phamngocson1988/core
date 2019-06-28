<?php
namespace common\models\promotions;

use Yii;

/**
 * PromotionBenefitAbstract model
 */
interface PromotionBenefitInterface
{
    public function render($form, $attr, $options = []);

    public function calculate($params);

    public function apply($params);
}