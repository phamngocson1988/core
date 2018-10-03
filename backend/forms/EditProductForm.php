<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;

class EditProductForm extends Model
{
    public $id;
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

    protected $_product;

    public function rules()
    {
        return [
            [['id', 'game_id', 'title', 'price', 'gems'], 'required'],
            ['id', 'validateProduct'],
            [['image_id', 'status', 'title'], 'trim'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE]
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $product = $this->getProduct();
                $product->title = $this->title;
                $product->price = $this->price;
                $product->gems = $this->gems;
                $product->sale_off_type = Product::SALE_TYPE;
                $product->status = Product::STATUS_VISIBLE;
                $product->updated_at = date('Y-m-d H:i:s');
                $product->updated_by = Yii::$app->user->id;
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

    public function validateProduct($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $product = $this->getProduct();
            if (!$product) {
                $this->addError($attribute, 'Invalid product.');
            }
        }
    }

    protected function getProduct()
    {
        if ($this->_product === null) {
            $this->_product = Product::findOne($this->id);
        }

        return $this->_product;
    }

    public function setProduct($product) 
    {
        if ($product instanceof Product) {
            $this->_product = $product;
        }
    }

    public function loadData($id)
    {
        $this->id = $id;
        $product = $this->getProduct();
        $this->title = $product->title;
        $this->game_id = $product->game_id;
        $this->price = $product->price;
        $this->sale_price = $product->sale_price;
        $this->sale_off_type = $product->sale_off_type;
        $this->sale_off_from = $product->sale_off_from;
        $this->sale_off_to = $product->sale_off_to;
        $this->gems = $product->gems;
        $this->image_id = $product->image_id;
        $this->status = $product->status;
    }

    public function getImageUrl($size)
    {
        $product = $this->getProduct();
        return $product->getImageUrl($size);
    }
}
