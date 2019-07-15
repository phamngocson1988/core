<?php
namespace common\models\realestate;

use Yii;

interface WaterInterface
{
    public function render($form, $attr, $options = []);

    public function calculate($from, $to);
}