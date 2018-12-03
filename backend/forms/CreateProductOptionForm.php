<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\ProductOption;

class CreateProductOptionForm extends Model
{
    const SCENARIO_NEW_PRODUCT = 'new_product';
    const SCENARIO_EDIT_PRODUCT = 'edit_product';

    public $title;
    public $product_id;
    public $price;
    public $gems;

    public function scenarios()
    {
        return [
            self::SCENARIO_NEW_PRODUCT => ['title', 'price', 'gems'],
            self::SCENARIO_EDIT_PRODUCT => ['title', 'product_id', 'price', 'gems'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required', 'on' => self::SCENARIO_EDIT_PRODUCT],
            [['title'], 'required'],
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

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $option = new ProductOption();
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
