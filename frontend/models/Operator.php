<?php
namespace frontend\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveQuery;

class Operator extends \common\models\Operator
{
	public function getViewUrl()
	{
		return Url::to(['operator/view', 'id' => $this->id, 'slug' => $this->slug]);
	}

	public static function find()
	{
		return new OperatorQuery(get_called_class());
	}
}

class OperatorQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}