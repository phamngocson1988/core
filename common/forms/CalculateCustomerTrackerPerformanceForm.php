<?php
namespace common\forms;

use Yii;
use common\models\CustomerTracker;
use common\models\Order;

class CalculateCustomerTrackerPerformanceForm extends ActionForm
{
    public $id; // lead_tracker_id

    protected $_customerTracker;

    public function rules()
    {
        return [
            ['id', 'trim'],
            ['id', 'required'],
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

    public function run()
    {
        if (!$this->validate()) return false;
        $tracker = $this->getCustomerTracker();
        $tracker->sale_month_1 = $tracker->sale_month_2 ? $tracker->sale_month_2 : $this->findTotalPayment($tracker->user_id, "-3 month");
        $tracker->sale_month_2 = $tracker->sale_month_3 ? $tracker->sale_month_3 : $this->findTotalPayment($tracker->user_id, "-2 month");
        $tracker->sale_month_3 = $this->findTotalPayment($tracker->user_id, "-1 month");
        $tracker->growth_rate_1 = $tracker->sale_month_2 - $tracker->sale_month_1;
        $tracker->growth_rate_2 = $tracker->sale_month_3 - $tracker->sale_month_2;
        $tracker->growth_speed = $tracker->growth_rate_2 - $tracker->growth_rate_1;
        $tracker->sale_growth = (max($tracker->sale_month_1, $tracker->sale_month_2, $tracker->sale_month_3) >= 150)
            && (min($tracker->growth_rate_1, $tracker->growth_rate_2, $tracker->gronth_speed) > 0);
        $tracker->product_growth = $this->findNumberOfGame($tracker->user_id) >= 2;
        if ($tracker->sale_target) {
            $tracker->kpi_growth = round($tracker->sale_month_3 / $tracker->sale_target, 2);
        }
        // $tracker->is_loyalty = true;
        return $tracker->save();
    }

    protected function findTotalPayment($userId, $month)
    {
        $start = date("Y-m-01 00:00:00", strtotime($month));
        $end = date("Y-m-t 23:59:59", strtotime($month));
        return Order::find()->where([
            'customer_id' => $userId,
            'status' => Order::STATUS_CONFIRMED
        ])
        ->andWhere(["between", "confirmed_at", $start,  $end])
        ->sum('quantity');
    }

    protected function findNumberOfGame($userId)
    {
        $start = date("Y-m-01 00:00:00", strtotime("-3 month"));
        $end = date("Y-m-t 23:59:59", strtotime("-1 month"));
        return Order::find()->where([
            'customer_id' => $userId,
            'status' => Order::STATUS_CONFIRMED
        ])
        ->andWhere(["between", "confirmed_at", $start,  $end])
        ->select('game_id')
        ->distinct()->count();
    }
}