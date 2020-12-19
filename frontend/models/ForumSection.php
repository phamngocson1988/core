<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class ForumSection extends \common\models\ForumSection
{
	public static function find()
	{
		return new ForumSectionQuery(get_called_class());
	}
}

class ForumSectionQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}