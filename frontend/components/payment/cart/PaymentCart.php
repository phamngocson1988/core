<?php
namespace frontend\components\payment\cart;

use Yii;
use yii\base\Model;

class PaymentCart extends Model
{
    public $title;
    public $items = [];
    public $discount;

    public function getItems()
    {
        return (array)$this->items;
    }

    public function setItems($items)
    {
        $this->items = (array)$items;
    }

    public function addItem($item)
    {
        if ($item instanceof PaymentItem) {
            $items = $this->getItems();
            $items[] = $item;
            $this->setItems($items);
        }
    }

    public function setDiscount($discount)
    {
        if ($discount instanceof PaymentDiscount) {
            $this->discount = $discount;
        }
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function hasDiscount()
    {
        $discount = $this->getDiscount();
        return ($discount) ? $discount->getTotalPrice() : 0;
    }
}