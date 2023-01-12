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
        $lastMonthSaleTarget = $tracker->getSaleTarget(date('Ym', strtotime('last month')));
        // sale performance
        if (!$tracker->first_order_at) {
            $tracker->first_order_at = $this->getFirstOrderDate($tracker->user_id);
            if ($tracker->first_order_at) {
                $tracker->is_normal_customer = true;
                $tracker->normal_customer_at = $now;
                $tracker->customer_monthly_status = max($tracker->customer_monthly_status, 1);
            }
        }
        $tracker->sale_month_1 = $this->findTotalPayment($tracker->user_id, "-3 month");
        $tracker->sale_month_2 = $this->findTotalPayment($tracker->user_id, "-2 month");
        $tracker->sale_month_3 = $this->findTotalPayment($tracker->user_id, "-1 month");
        $tracker->growth_rate_1 = $tracker->sale_month_2 - $tracker->sale_month_1;
        $tracker->growth_rate_2 = $tracker->sale_month_3 - $tracker->sale_month_2;
        $tracker->growth_speed = $tracker->growth_rate_2 - $tracker->growth_rate_1;
        $tracker->sale_growth = (max($tracker->sale_month_1, $tracker->sale_month_2, $tracker->sale_month_3) >= 150)
            && (min($tracker->growth_rate_1, $tracker->growth_rate_2, $tracker->growth_speed) > 0);
        $tracker->number_of_game = $this->findNumberOfGame($tracker->user_id);
        $tracker->product_growth = $tracker->number_of_game >= 2;
        if ($lastMonthSaleTarget) {
            $tracker->kpi_growth = round($tracker->sale_month_3 / $lastMonthSaleTarget, 2);
        }
        $lastOrder3Months = $this->getLastOrder($tracker->user_id, 3);
        $is_potential_customer = max($tracker->sale_month_1, $tracker->sale_month_2, $tracker->sale_month_3) >= 105;
        if ($is_potential_customer && !$tracker->potential_customer_at) {
            $tracker->potential_customer_at = $lastOrder3Months;
            $tracker->customer_monthly_status = max($tracker->customer_monthly_status, 2);
        }
        $tracker->is_potential_customer = $is_potential_customer;
        
        $is_key_customer = (max($tracker->sale_month_1, $tracker->sale_month_2, $tracker->sale_month_3) >= 150)
            && ((float)$tracker->kpi_growth >= 0.7);
        if ($is_key_customer && !$tracker->key_customer_at) {
            $tracker->key_customer_at = $lastOrder3Months;
            $tracker->customer_monthly_status = max($tracker->customer_monthly_status, 3);
        }
        $tracker->is_key_customer = $is_key_customer;
        $is_loyalty = $this->checkLoyalty($tracker->user_id);
        if ($is_loyalty !== $tracker->is_loyalty) {
            $tracker->is_loyalty = $is_loyalty;
            $tracker->loyalty_customer_updated_at = $this->getLastOrder($tracker->user_id, 6);
            if ($tracker->is_loyalty && !$tracker->loyalty_customer_at) {
                $tracker->loyalty_customer_at = $tracker->loyalty_customer_updated_at;
            }
        }
        

        $is_dangerous = max($tracker->growth_rate_1, $tracker->growth_rate_2) < 0;
        if ($is_dangerous !== $tracker->is_dangerous) {
            $tracker->is_dangerous = $is_dangerous;
            $tracker->dangerous_customer_updated_at = $lastOrder3Months;
            if ($tracker->is_dangerous && !$tracker->dangerous_customer_at) {
                $tracker->dangerous_customer_at = $lastOrder3Months;
            }
        }
        
        $tracker->monthly_sale_volumn = round(($tracker->sale_month_1 + $tracker->sale_month_2 + $tracker->sale_month_3) / 3, 2);
        $tracker->daily_sale_volumn = $this->getDailySaleAvg($tracker->user_id);
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

    protected function getLastOrder($userId, $month) 
    {
        $month = "-$month month";
        $start = date("Y-m-01 00:00:00", strtotime($month));
        $end = date("Y-m-t 23:59:59", strtotime($month));
        $order = Order::find()
        ->where(['customer_id' => $userId])
        ->andWhere(["between", "created_at", $start,  $end])
        ->orderBy("id desc")
        ->select(['created_at'])->one();
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

    protected function getDailySaleAvg($userId)
    {
        $month = "-1 month";
        $start = date("Y-m-01 00:00:00", strtotime($month));
        $end = date("Y-m-t 23:59:59", strtotime($month));
        $check = Order::find()
            ->where(['customer_id' => $userId])
            ->andWhere(["between", "created_at", $start,  $end])
            ->average("quantity");
    }
}