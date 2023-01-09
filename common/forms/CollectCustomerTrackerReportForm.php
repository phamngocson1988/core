<?php
namespace common\forms;

use Yii;
use common\models\CustomerTracker;
use common\models\LeadTrackerPeriodic;
use common\models\Order;

class CollectCustomerTrackerReportForm extends ActionForm
{
    public $id; // lead_tracker_id
    public $month;
    public $year;

    protected $_customerTracker;
    protected $_trackerPeriodic;

    public function rules()
    {
        return [
            [['id', 'month', 'year'], 'trim'],
            [['id', 'month', 'year'], 'required'],
            ['id', 'validateTracker']
        ];
    }

    public function validateTracker($attribute) 
    {
        $tracker = $this->getCustomerTracker();
        if (!$tracker) {
            return $this->addError($attribute, 'Customer tracker is not exist');
        }
    }

    public function getCustomerTracker() 
    {
        if (!$this->_customerTracker) {
            $this->_customerTracker = CustomerTracker::findOne($this->id);
        }
        return $this->_customerTracker;
    }

    public function getTrackerPeriodic()
    {
        if (!$this->_trackerPeriodic) {
            $condition = [
                'month' => "$this->year$this->month",
                'lead_tracker_id' => $this->id,
            ];
            $this->_trackerPeriodic = LeadTrackerPeriodic::findOne($condition);
            if (!$this->_trackerPeriodic) {
                $this->_trackerPeriodic = new LeadTrackerPeriodic($condition);
            }
        }
        return $this->_trackerPeriodic;
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $y = $this->year;
        $m = $this->month;
        $start = "$y-$m-01 00:00:00";
        $end = date("Y-m-t 23:59:59", strtotime($start));

        $tracker = $this->getCustomerTracker();
        $periodic = $this->getTrackerPeriodic();
        $periodic->quantity = $this->findTotalPayment($tracker->user_id, $start, $end);
        $periodic->target = $tracker->getSaleTarget("$y$m");
        if ($tracker->key_customer_at && strtotime($end) >= strtotime($tracker->key_customer_at)) {
            $periodic->monthly_status = 3;
        } elseif ($tracker->potential_customer_at && strtotime($end) >= strtotime($tracker->potential_customer_at)) { 
            $periodic->monthly_status = 2;
        } elseif ($tracker->potential_customer_at && strtotime($end) >= strtotime($tracker->potential_customer_at)) {
            $periodic->monthly_status = 1;
        }
        $periodic->is_loyalty = $tracker->is_loyalty && $tracker->loyalty_customer_updated_at && strtotime($end) >= strtotime($tracker->loyalty_customer_updated_at);
        $periodic->is_dangerous = $tracker->is_dangerous && $tracker->dangerous_customer_updated_at && strtotime($end) >= strtotime($tracker->dangerous_customer_updated_at);
        return $periodic->save();
    }

    protected function findTotalPayment($userId, $start, $end)
    {
        return Order::find()->where([
            'customer_id' => $userId,
            'status' => Order::STATUS_CONFIRMED
        ])
        ->andWhere(["between", "confirmed_at", $start,  $end])
        ->sum('quantity');
    }

}