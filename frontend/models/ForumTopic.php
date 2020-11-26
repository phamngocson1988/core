<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class ForumTopic extends \common\models\ForumTopic
{
	public static function find()
	{
		return new ForumTopicQuery(get_called_class());
	}
}

class ForumTopicQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}