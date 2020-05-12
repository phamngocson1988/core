<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class Package extends \common\models\Package
{
	public static function find()
	{
		return new PackageQuery(get_called_class());
	}
}

class PackageQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['status' => Package::STATUS_VISIBLE]);
        parent::init();
    }
}