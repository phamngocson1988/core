<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class ForumCategory extends \common\models\ForumCategory
{
	public static function find()
	{
		return new ForumCategoryQuery(get_called_class());
	}
}

class ForumCategoryQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}