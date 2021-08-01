<?php

namespace console\forms;

use Yii;
use common\models\Order;

class AutoConfirmOrderForm extends ActionForm
{
    public $duration = 72;
    public $startTime = '2021-07-10 00:00:00';

    protected $_countResult = 0;
    protected $_successIds = [];
    protected $_failureIds = [];

    public function rules() 
    {
        return [
            ['duration', 'required'],
        ];
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $orders = $this->fetchOrders();
        $this->_countResult = count($orders);
        foreach ($orders as $id => $order) {
            // echo sprintf("Auto confirm %s \n", $id);
            try {
                $confirmForm = new ConfirmOrderForm(['id' => $id]);
                $confirmForm->setOrder($order);
                if ($confirmForm->save()) {
                    $this->_successIds[] = $id;
                } else {
                    $this->_failureIds[] = $id;
                }
            } catch(Exception $e) {
                $this->_failureIds[] = $id;
            }
        }
        
    }

    public function fetchOrders()
    {
        $now = date('Y-m-d H:i:s');
        return Order::find()
        ->where(['status' => Order::STATUS_COMPLETED])
        ->andWhere(['>=', 'completed_at', $this->startTime])
        ->andWhere(['>=', sprintf("TIMESTAMPDIFF(HOUR, completed_at, '%s')", $now), $this->duration])
        ->andWhere(['IS NOT', 'completed_at', new \yii\db\Expression('NULL')])
        ->indexBy('id')
        ->all();
    }

    public function getCountResult()
    {
        return $this->_countResult;
    }
    public function getSuccessIds()
    {
        return $this->_successIds;
    }
    public function getFailureIds()
    {
        return $this->_failureIds;
    }
    
}
