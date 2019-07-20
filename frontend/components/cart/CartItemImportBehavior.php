<?php 
namespace frontend\components\cart;

use yii\base\Behavior;

class CartItemImportBehavior extends Behavior
{
    public $row_index;
    public $no;

    public function getPrice() : int
    {
        return ($this->reseller_price) ? (int)$this->reseller_price : (int)$this->price;
    }

    public function getTotalPrice()
    {
        return 1;
    }
}