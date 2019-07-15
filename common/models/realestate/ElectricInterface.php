<?php
namespace common\models\realestate;

use Yii;

interface ElectricInterface
{
    public function render($form, $attr, $options = []);

    public function calculate($from, $to);
}