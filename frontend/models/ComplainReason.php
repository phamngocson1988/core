<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class ComplainReason extends \common\models\ComplainReason
{
	public static function find()
	{
		return new ComplainReasonQuery(get_called_class());
	}
}

class ComplainReasonQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}