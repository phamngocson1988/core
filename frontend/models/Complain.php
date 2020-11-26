<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class Complain extends \common\models\Complain
{
	public function getIcon()
	{
		return sprintf("/img/complain/%s.png", $this->status);
	}

	public static function find()
	{
		return new ComplainQuery(get_called_class());
	}
}

class ComplainQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['language' => Yii::$app->language]);
        parent::init();
    }
}