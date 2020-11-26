<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class Bonus extends \common\models\Bonus
{
	public static function find()
	{
		return new BonusQuery(get_called_class());
	}
}

class BonusQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}