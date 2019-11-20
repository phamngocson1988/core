<?php
namespace reseller\components\payment\cart;

use Yii;
use yii\base\Model;

class PaymentPromotion extends Model
{
    public $id;
    public $title;
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
        return 1;
    }

    public function getPrice()
    {
        return round((float)$this->price, 1);
    }
    
    public function getTotalPrice()
    {
        return $this->getPrice();
    }
}