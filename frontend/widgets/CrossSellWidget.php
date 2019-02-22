<?php
namespace frontend\widgets;

use yii\base\Widget;

class CrossSellWidget extends Widget
{
    public $product_id;

    public function run()
    {
        return $this->render('cross_sell');
    }
}