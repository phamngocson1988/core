<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;

class DeleteProductForm extends Model
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
            $transaction = Yii::$app->db->beginTransaction();
            $product = $this->getProduct();
            try {
            	$result = $product->delete();
                $transaction->commit();
                return $result;
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
