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

  /**
   * @var $month string 
   * @example 202212
   */
  public function getSaleTarget($month)
  {
    try {
      $data = json_decode($this->sale_target, true);
      return ArrayHelper::getValue($data, $month, 0);
    } catch (Exception $e) {
      return 0;
    }
  }

  public function getCurrentSaleTarget()
  {
    $month = date("Ym");
    return $this->getSaleTarget($month);
  }

  public function setSaleTarget($month, $value) 
  {
    try {
      $data = json_decode($this->sale_target, true);
    } catch (Exception $e) {
      $data = [];
    }
    $data[$month] = $value;
    $this->sale_target = json_encode($data);
  }

  public function setCurrentSaleTarget($value)
  {
    $month = date("Ym");
    return $this->setSaleTarget($month, $value);
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
