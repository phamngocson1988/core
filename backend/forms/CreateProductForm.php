<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;

class CreateProductForm extends Model
{
    public $title;
    public $price;
    public $gems;

    public function rules()
    {
        return [
            [['title', 'price', 'gems'], 'required'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['title', 'price', 'gems'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $product = new Product();
                $product->title = $this->title;
                $product->price = $this->price;
                $product->gems = $this->gems;
                $product->sale_off_type = "fix";
                $product->status = "Y";
                return $product->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}
