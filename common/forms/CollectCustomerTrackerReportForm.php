<?php
namespace common\forms;

use Yii;
use common\models\CustomerTracker;
use common\models\Order;

class CollectCustomerTrackerReportForm extends ActionForm
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