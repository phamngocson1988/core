<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * LeadTracker model
 */
class LeadTracker extends UserTracker
{
    public static function find()
	{
		return new LeadTrackerQuery(get_called_class());
	}
}

class LeadTrackerQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['is', 'user_id', new \yii\db\Expression('null')]);
        parent::init();
    }
}
