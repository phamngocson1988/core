<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;

class FetchNewPendingOrderForm extends Model
{
    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Order::find();
        $command->where(['status' => Order::STATUS_PENDING]);
        $command->andWhere(['handler_id' => null]);
        $this->_command = $command;

    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
}
