<?php
namespace frontend\components\payment\cart;

use Yii;
use yii\base\Model;

class PaymentCart extends Model
{
    public $title;
    public $items = [];
    public $discount;
    public $promotion;

    public function getTitle()
    {
        return $this->title;
    }
    
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

    public function setPromotion($promotion)
    {
        if ($promotion instanceof PaymentDiscount) {
            $this->promotion = $promotion;
        }
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function getPromotion()
    {
        return $this->promotion;
    }

    public function hasDiscount()
    {
        $discount = $this->getDiscount();
        return ($discount) ? $discount->getTotalPrice() : 0;
    }

    public function hasPromotion()
    {
        $promotion = $this->getPromotion();
        return ($promotion) ? $promotion->getTotalPrice() : 0;   
    }

    public function getTotalPrice()
    {
        $subTotal = $this->getSubTotalPrice();
        $discount = $this->hasDiscount();
        return $subTotal - $discount;
    }

    public function getSubTotalPrice()
    {
        $prices = array_map(function($item) {
            return $item->getTotalPrice();
        }, $this->getItems());
        return array_sum($prices);
    }
}