<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\User;
use backend\models\Game;
use backend\models\Supplier;
use backend\models\OrderSupplier;

class ReportOrderProfitForm extends Model
{
    public $id;
    public $confirmed_from;
    public $confirmed_to;
    public $payment_method;

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $orderTable = Order::tableName();
        $orderSupplierTable = OrderSupplier::tableName();
        $command = Order::find();
        $orderSupplierConfirmedStatus = OrderSupplier::STATUS_CONFIRMED;
        $command->leftJoin($orderSupplierTable, "{$orderTable}.id = {$orderSupplierTable}.order_id AND $orderSupplierTable.status = '$orderSupplierConfirmedStatus'");
        // $command->andWhere(["$orderSupplierTable.status" => OrderSupplier::STATUS_CONFIRMED]);
        
        if ($this->id) {
            $command->andWhere(["{$orderTable}.id" => $this->id]);
        }
        if ($this->confirmed_from) {
            $command->andWhere(['>=', "$orderTable.confirmed_at", $this->confirmed_from]);
        }
        if ($this->confirmed_to) {
            $command->andWhere(['<=', "$orderTable.confirmed_at", $this->confirmed_to]);
        }
        if ($this->payment_method) {
            $command->andWhere(["$orderTable.payment_method" => $this->payment_method]);
        }
        $command->select([
            "{$orderTable}.id as order_id", 
            "{$orderTable}.quantity as order_quantity", 
            "{$orderTable}.doing_unit as order_doing", 
            "({$orderTable}.total_price * {$orderTable}.rate_usd / {$orderTable}.quantity) as order_price",
            "IF({$orderSupplierTable}.supplier_id, {$orderTable}.total_price * {$orderTable}.rate_usd * {$orderSupplierTable}.doing / {$orderTable}.quantity, {$orderTable}.total_price * {$orderTable}.rate_usd) as order_total_price",
            "{$orderTable}.customer_name", 
            "{$orderTable}.confirmed_at", 
            "{$orderTable}.game_title", 
            "{$orderTable}.customer_name", 
            "{$orderSupplierTable}.supplier_id",
            "COALESCE({$orderSupplierTable}.quantity, 0) as supplier_quantity", 
            "COALESCE({$orderSupplierTable}.doing, 0) as supplier_doing",
            "COALESCE({$orderSupplierTable}.price, 0) as supplier_price",
            "COALESCE({$orderSupplierTable}.total_price, 0) as supplier_total_price",
            "({$orderTable}.total_price * {$orderTable}.rate_usd * IF({$orderSupplierTable}.supplier_id, {$orderSupplierTable}.doing / {$orderTable}.quantity, 1) - COALESCE({$orderSupplierTable}.total_price, 0)) as profit",
        ]);
        $command->orderBy(["{$orderTable}.confirmed_at" => SORT_DESC]);
        $command->asArray();
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchPaymentMethods()
    {
        return [
            'kinggems' => 'King Coin',
            'alipay' => 'Alipay',
            'skrill' => 'Skrill',
            'alipay' => 'Alipay',
            'wechat' => 'Wechat',
            'postal-savings-bank-of-china' => 'Postal Savings Bank Of China',
            'payoneer' => 'Payoneer',
            'bitcoin' => 'Bitcoin',
            'western_union' => 'Western Union',
            'neteller' => 'Neteller',
            'standard_chartered' => 'Standard Chartered',
            'paypal' => 'Paypal',
        ];   
    }
}
