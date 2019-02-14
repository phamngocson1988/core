<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\ProductImage;
use yii\helpers\ArrayHelper;

class EditProductForm extends Model
{
    public $id;
    public $title;
    public $game_id;
    public $image_id;
    public $price;
    public $unit;
    public $status = Product::STATUS_VISIBLE;

    protected $_product;

    public function rules()
    {
        return [
            [['id', 'title', 'game_id', 'price', 'unit'], 'required'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE],
            ['id', 'validateProduct'],
            [['image_id'], 'safe']
        ];
    }

    public function attributeLabels() 
    { 
        $product = $this->getProduct();
        return  [
            'id' => Yii::t('app', 'id'),
            'title' => Yii::t('app', 'title'),
            'game_id' => Yii::t('app', 'game_id'),
            'status' => Yii::t('app', 'status'),
            'price' => Yii::t('app', 'product_options'),
            'unit' => $product->unit_name,
            'image_id' => Yii::t('app', 'image'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $product = $this->getProduct();
                $product->title = $this->title;
                $product->price = $this->price;
                $product->image_id = $this->image_id;
                $product->unit = $this->unit;
                $product->status = $this->status;
                if (!$product->save()) {
                    throw new Exception("Error Processing Request", 1);
                }
                $transaction->commit();
                $this->_product = $product;
                return $product;
            } catch (Exception $e) {
                $transaction->rollBack();                
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return false;
    }

    public function getStatusList()
    {
        return Product::getStatusList();
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

    public function getProduct()
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
            $this->id = $product->id;
        }
    }

    public function loadData($id)
    {
        $this->id = $id;
        $product = $this->getProduct();
        $this->title = $product->title;
        $this->game_id = $product->game_id;
        $this->price = $product->price;
        $this->image_id = $product->image_id;
        $this->unit = $product->unit;
        $this->status = $product->status;
    }
}
