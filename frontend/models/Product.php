<?php
namespace frontend\models;

use yii\db\ActiveQuery;

class Product extends \common\models\Product
{
	public static function find()
	{
		return new ProductQuery(get_called_class());
	}
}

class ProductQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['status' => Product::STATUS_VISIBLE]);
        parent::init();
    }
}
