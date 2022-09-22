<?php
namespace common\forms;

use Yii;
use common\models\Order;
use common\models\OrderSupplier;
use yii\helpers\ArrayHelper;

class RunOrderCommissionForm extends ActionForm
{
    public $order_id; // order
    public $profit_rate = 0.6;
    public $flat_commission = 10000;

    protected $_order;

    public function rules()
    {
        return [
            ['order_id', 'trim'],
            ['order_id', 'required'],
            ['order_id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute) 
    {
        $order = $this->getOrder();
        if (!$order) {
            return $this->addError($attribute, 'Order is not exist');
        }

        if (!$order->isConfirmedOrder()) {
            return $this->addError($attribute, 'Order has not confirmed yet');
        }
    }

    public function getOrder() 
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->order_id);
        }
        return $this->_order;
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $game = $order->game;
        if (!$game || !$game->expected_profit) {
          return true;
        }
        $suppliers = OrderSupplier::find()
            ->where([
              'order_id' => $order->id,
              'status' => OrderSupplier::STATUS_CONFIRMED
            ])
            ->all();
        if (!count($suppliers)) {
          return true;
        }

        $expectedProfit = $order->quantity * $game->expected_profit;
        $realOutcome = array_sum(ArrayHelper::getColumn($suppliers, 'total_price'));
        $realIncome = $order->quantity * $order->price * $order->rate_usd;
        $overcome = $realIncome - $realOutcome;
        $commission = max(0, $overcome - $expectedProfit);
        $selloutCommission = $this->flat_commission * $order->quantity / 2;
        $order->expected_profit = $expectedProfit;
        $order->real_profit = $overcome;
        $order->profit_rate = $this->profit_rate;
        if ($order->saler_id) {
          $order->saler_order_commission = max(0, ((1 - $this->profit_rate) / 2) * $commission);
          $order->saler_sellout_commission = $commission ? $selloutCommission : 0;
        } else {
          $order->saler_order_commission = 0;
          $order->saler_sellout_commission = 0;
        }
        if ($order->orderteam_id) {
          $order->orderteam_order_commission = max(0, ((1 - $this->profit_rate) / 2) * $commission);
          $order->orderteam_sellout_commission = $commission ? $selloutCommission : 0;
        } else {
          $order->orderteam_order_commission = 0;
          $order->orderteam_sellout_commission = 0;
        }
        $order->save();
        return true;
    }
}