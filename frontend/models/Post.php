<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class Post extends \common\models\Post
{
	public static function find()
	{
		return new PostQuery(get_called_class());
	}
}

class PostQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}