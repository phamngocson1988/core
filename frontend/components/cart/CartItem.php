<?php
namespace frontend\components\cart;

use Yii;
use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;

class CartItem extends Model implements CartItemInterface
{
    /** @var Product */
    public $product;
    /** @var ProductOption */
    public $option;

    public function getPrice()
    {
        return $this->option->price;
    }

    public function getLabel()
    {
        return sprintf("%s - %s", $this->product->title, $this->option->title);
    }

    public function getUniqueId()
    {
        return sprintf("%s_%s", $this->product->id, $this->option->id);
    }
}