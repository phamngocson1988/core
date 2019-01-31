<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;

class CreateProductForm extends Model
{
    public $title;
    public $game_id;
    public $image_id;
    public $price;
    public $gems;
    public $status = Product::STATUS_VISIBLE;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'game_id', 'price', 'gems'], 'required'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE],
            [['image_id'], 'safe']
        ];
    }

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'game_id' => Yii::t('app', 'game_id'),
            'image_id' => Yii::t('app', 'image'),
            'price' => Yii::t('app', 'price'),
            'gems' => Yii::t('app', 'gems'),
            'status' => Yii::t('app', 'status'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $product = new Product();
                $product->title = $this->title;
                $product->game_id = $this->image_id;
                $product->image_id = $this->image_id;
                $product->price = $this->price;
                $product->gems = $this->gems;
                $product->created_by = Yii::$app->user->id;
                $product->status = $this->status;
                if (!$product->save()) {
                    throw new Exception("Error Processing Request", 1);
                }
                
                $transaction->commit();
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
}
