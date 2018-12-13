<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\ProductOption;

class EditProductOptionForm extends Model
{
    public $id;
    public $title;
    public $product_id;
    public $price;
    public $gems;

    protected $_option;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'product_id'], 'required'],
            ['id', 'validateProductOption'],
            [['price', 'gems'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'product_id' => Yii::t('app', 'product_id'),
            'price' => Yii::t('app', 'price'),
            'gems' => Yii::t('app', 'gems'),
        ];
    }

    public function validateProductOption($attribute, $params)
    {
        $option = $this->getProductOption();
        if (!$option) {
            $this->addError($attribute, Yii::t('app', 'invalid_product_option'));
        }
    }

    protected function getProductOption()
    {
        if ($this->_option === null) {
            $this->_option = ProductOption::findOne($this->id);
        }

        return $this->_option;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $option = $this->getProductOption();
                $option->title = $this->title;
                $option->product_id = $this->product_id;
                $option->price = $this->price;
                $option->gems = $this->gems;
                if ($option->save()) {
                	$transaction->commit();
                	return $option;
                } else {
                	$transaction->rollBack();
                	throw new Exception("Error Processing Request", 1);
                }
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
}
