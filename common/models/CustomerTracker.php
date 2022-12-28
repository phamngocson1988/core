<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * CustomerTracker model
 */
class CustomerTracker extends UserTracker
{
  public static function find()
	{
		return new CustomerTrackerQuery(get_called_class());
	}

  public function getCustomerTrackerStatus()
  {
    return $this->customer_tracker_status;
  }
  public function getCustomerTrackerLabel()
  {
    return ArrayHelper::getValue(self::CUSTOMER_STATUS, $this->getCustomerTrackerStatus(), '');
  }

  public function getCustomerMonthlyLabel()
  {
    return ArrayHelper::getValue(self::CUSTOMER_STATUS, $this->customer_monthly_status, 'Normal Custormer');
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
