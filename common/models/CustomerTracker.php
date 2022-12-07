<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * CustomerTracker model
 */
class CustomerTracker extends UserTracker
{
  public static function find()
	{
		return new CustomerTrackerQuery(get_called_class());
	}
}

class CustomerTrackerQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['is not', 'user_id', new \yii\db\Expression('null')]);
        parent::init();
    }
}
