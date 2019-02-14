<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;

class ChangeProductStatusForm extends Model
{
    public $id;

    private $_product;

	public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateProduct'],
        ];
    }

    public function delete()
    {
    	if ($this->validate()) {
            $product = $this->getProduct();
            $product->status = Product::STATUS_DELETE;
            return $product->save();
        }
        return false;
    }

    public function enable()
    {
        if ($this->validate()) {
            $product = $this->getProduct();
            $product->status = Product::STATUS_VISIBLE;
            return $product->save();
        }
        return false;
    }

    public function disable()
    {
        if ($this->validate()) {
            $product = $this->getProduct();
            $product->status = Product::STATUS_INVISIBLE;
            return $product->save();
        }
        return false;
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
}
