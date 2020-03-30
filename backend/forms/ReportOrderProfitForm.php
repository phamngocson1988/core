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
        $command->leftJoin($orderTable, "{$orderTable}.id = {$orderSupplierTable}.order_id AND $orderSupplierTable.status = '$orderSupplierConfirmedStatus'");
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
