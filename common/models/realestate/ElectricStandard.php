<?php
namespace common\models\realestate;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ElectricStandard extends ElectricAbstract implements ElectricInterface
{
    public function render($form, $attr, $options = [])
    {
        return '';
    }

    public function calculate($from, $to)
    {
        return 0;
    }
}