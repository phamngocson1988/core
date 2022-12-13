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
        $now = date('Y-m-d H:i:s');
        // sale performance
        if (!$tracker->first_order_at) {
            $tracker->first_order_at = $this->getFirstOrderDate($tracker->user_id);
        }
        $tracker->sale_month_1 = $this->findTotalPayment($tracker->user_id, "-3 month");
        $tracker->sale_month_2 = $this->findTotalPayment($tracker->user_id, "-2 month");
        $tracker->sale_month_3 = $this->findTotalPayment($tracker->user_id, "-1 month");
        $tracker->growth_rate_1 = $tracker->sale_month_2 - $tracker->sale_month_1;
        $tracker->growth_rate_2 = $tracker->sale_month_3 - $tracker->sale_month_2;
        $tracker->growth_speed = $tracker->growth_rate_2 - $tracker->growth_rate_1;
        $tracker->sale_growth = (max($tracker->sale_month_1, $tracker->sale_month_2, $tracker->sale_month_3) >= 150)
            && (min($tracker->growth_rate_1, $tracker->growth_rate_2, $tracker->gronth_speed) > 0);
        $tracker->number_of_game = $this->findNumberOfGame($tracker->user_id);
        $tracker->product_growth = $this->number_of_game >= 2;
        if ($tracker->sale_target) {
            $tracker->kpi_growth = round($tracker->sale_month_3 / $tracker->sale_target, 2);
        }
        $is_potential_customer = max($tracker->sale_month_1, $tracker->sale_month_2, $tracker->sale_month_3) >= 150;
        if ($is_potential_customer && !$tracker->is_potential_customer) {
            $tracker->potential_customer_at = $now;
        }
        $tracker->is_potential_customer = $is_potential_customer;
        
        $is_key_customer = (max($tracker->sale_month_1, $tracker->sale_month_2, $tracker->sale_month_3) >= 150)
            && ((float)$tracker->kpi_growth >= 0.7);
        if ($is_key_customer && !$tracker->is_key_customer) {
            $tracker->key_customer_at = $now;
        }
        $tracker->is_key_customer = $is_key_customer;
        $tracker->is_loyalty = $this->checkLoyalty($tracker->user_id);
        $tracker->is_dangerous = max($tracker->growth_rate_1, $tracker->growth_rate_2) < 0;
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

    protected function getFirstOrderDate($userId) 
    {
        $order = Order::find()->where(['customer_id' => $userId])->select(['created_at'])->one();
        return $order ? $order->created_at : null;
    }

    protected function checkLoyalty($userId)
    {
        for ($i = 1; $i <= 6; $i++) {
            $month = "-$i month";
            $start = date("Y-m-01 00:00:00", strtotime($month));
            $end = date("Y-m-t 23:59:59", strtotime($month));
            $check = Order::find()
                ->where(['customer_id' => $userId])
                ->andWhere(["between", "created_at", $start,  $end])
                ->exists();
            if (!$check) {
                return false;
            }
        }
        return true;
    }
}