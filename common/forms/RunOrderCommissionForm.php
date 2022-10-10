<?php
namespace common\forms;

use Yii;
use common\models\Order;
use common\models\OrderSupplier;
use common\models\OrderCommission;
use common\models\User;
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

        $expectedProfit = $order->quantity * $game->expected_profit * $order->rate_usd;
        $realOutcome = array_sum(ArrayHelper::getColumn($suppliers, 'total_price'));
        $realIncome = $order->quantity * $order->price * $order->rate_usd;
        $overcome = $realIncome - $realOutcome;
        $commission = max(0, $overcome - $expectedProfit);
        $selloutCommission = $this->flat_commission * $order->quantity / 2;

        // Save for order
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

        // save for order_commission
        if ($commission >= 0) {
          if ($order->saler_id) {
            $saler = User::findOne($order->saler_id);
            $orderCommissionDescriptionSaler = json_encode([
              'expected_profit' => $order->expected_profit,
              'real_profit' => $order->real_profit,
              'profit_rate' => $order->profit_rate,
              'user_profit_rate' => $this->getUserRateProfit()
            ]);
            $this->generateOrderCommission($order, $saler, OrderCommission::COMMSSION_TYPE_ORDER, OrderCommission::USER_ROLE_SALER, $order->saler_order_commission, $orderCommissionDescriptionSaler);
            $selloutCommissionDescriptionSaler = json_encode([
              'expected_profit' => $order->expected_profit,
              'real_profit' => $order->real_profit,
              'commission' => $commission,
              'sellout_commission' => $order->saler_sellout_commission
            ]);
            $this->generateOrderCommission($order, $saler, OrderCommission::COMMSSION_TYPE_SELLOUT, OrderCommission::USER_ROLE_SALER, $order->saler_sellout_commission, $selloutCommissionDescriptionSaler);
          }
          if ($order->orderteam_id) {
            $orderteam = User::findOne($order->orderteam_id);
            $orderCommissionDescriptionOT = json_encode([
              'expected_profit' => $order->expected_profit,
              'real_profit' => $order->real_profit,
              'profit_rate' => $order->profit_rate,
              'user_profit_rate' => $this->getUserRateProfit()
            ]);
            $this->generateOrderCommission($order, $orderteam, OrderCommission::COMMSSION_TYPE_ORDER, OrderCommission::USER_ROLE_ORDERTEAM, $order->orderteam_order_commission, $orderCommissionDescriptionOT);
            $selloutCommissionDescriptionOT = json_encode([
              'expected_profit' => $order->expected_profit,
              'real_profit' => $order->real_profit,
              'commission' => $commission,
              'sellout_commission' => $order->orderteam_sellout_commission
            ]);
            $this->generateOrderCommission($order, $orderteam, OrderCommission::COMMSSION_TYPE_SELLOUT, OrderCommission::USER_ROLE_ORDERTEAM, $order->orderteam_sellout_commission, $selloutCommissionDescriptionOT);
          }
        }
        return true;
    }

    protected function generateOrderCommission($order, $user, $type, $role, $amount, $description) 
    {
      $orderCommission = OrderCommission::findOne([
        'order_id' => $order->id,
        'user_id' => $user->id,
        'commission_type' => $type,
        'role' => $role
      ]);
      if (!$orderCommission) {
        $orderCommission = new OrderCommission();
      }
      $orderCommission->order_id = $order->id;
      $orderCommission->user_id = $user->id;
      $orderCommission->commission_type = $type;
      $orderCommission->role = $role;
      $orderCommission->username = $user->username;
      $orderCommission->user_commission = $amount;
      $orderCommission->description = $description;
      $orderCommission->created_at = $order->confirmed_at;
      return $orderCommission->save();
    }

    protected function getUserRateProfit()
    {
      return (1 - $this->profit_rate) / 2; // balance for saler and orderteam
    }
}