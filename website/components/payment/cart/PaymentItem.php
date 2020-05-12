<?php
namespace website\components\payment\cart;

use Yii;
use yii\base\Model;

class PaymentItem extends Model
{
    public $id;
    public $title;
    public $quantity;
    public $price;    

    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function getQuantity()
    {
        return (float)$this->quantity;
    }

    public function getPrice()
    {
        return round((float)$this->price, 1);
    }
    
    public function getTotalPrice()
    {
        return round($this->getPrice() * $this->getQuantity(), 1);
    }
}