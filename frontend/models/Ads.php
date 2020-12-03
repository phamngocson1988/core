<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class Ads extends \common\models\Ads
{
	public static function find()
	{
		return new AdsQuery(get_called_class());
	}
}

class AdsQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}