<?php
namespace frontend\components\cart;

class CartItemReseller extends CartItem
{
	public $row_index;
    public $no;

    public function getPrice() : int
    {
        return ($this->reseller_price) ? (int)$this->reseller_price : (int)$this->price;
    }
}