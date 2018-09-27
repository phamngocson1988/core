<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;

class EditProductForm extends Model
{
    public $game_id;
    public $title;
    public $price;
    public $sale_price;
    public $sale_off_type;
    public $sale_off_from;
    public $sale_off_to;
    public $gems;
    public $image_id;
    public $status;

    public function rules()
    {
        return [
            [['game_id', 'title', 'price', 'gems'], 'required'],
            [['image_id', 'status'], 'trim'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE]
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $product = new Product();
                $product->title = $this->title;
                $product->price = $this->price;
                $product->gems = $this->gems;
                $product->sale_off_type = Product::SALE_TYPE;
                $product->status = Product::STATUS_VISIBLE;
                return $product->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function getStatusList()
    {
        return Product::getStatusList();
    }

    public function getSaleTypeList()
    {
        return Product::getSaleTypeList();
    }
}
