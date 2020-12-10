<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class Category extends \common\models\Category
{
	public static function find()
	{
		return new CategoryQuery(get_called_class());
	}
}

class CategoryQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}