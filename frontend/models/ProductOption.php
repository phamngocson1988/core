<?php
namespace frontend\models;

use yii\db\ActiveQuery;

class ProductOption extends \common\models\ProductOption
{
	public static function find()
	{
		return new ProductOptionQuery(get_called_class());
	}
}

class ProductOptionQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['status' => ProductOption::STATUS_VISIBLE]);
        parent::init();
    }
}
