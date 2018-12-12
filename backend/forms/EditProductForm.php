<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;

class EditProductForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $excerpt;
    public $image_id;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status = Product::STATUS_VISIBLE;
    public $gallery = [];
    public $options = [];

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

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'options' => Yii::t('app', 'product_options'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'image_id' => Yii::t('app', 'image'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
            'gallery' => Yii::t('app', 'gallery'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $product = $this->getProduct();
                $product->title = $this->title;
                $product->content = $this->content;
                $product->excerpt = $this->excerpt;
                $product->image_id = $this->image_id;
                $product->meta_title = $this->meta_title;
                $product->meta_keyword = $this->meta_keyword;
                $product->meta_description = $this->meta_description;
                $product->status = $this->status;
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
        $this->content = $product->content;
        $this->excerpt = $product->excerpt;
        $this->image_id = $product->image_id;
        $this->meta_title = $product->meta_title;
        $this->meta_keyword = $product->meta_keyword;
        $this->meta_description = $product->meta_description;
        $this->status = $product->status;
    }

    public function getImageUrl($size)
    {
        $product = $this->getProduct();
        return $product->getImageUrl($size);
    }
}
