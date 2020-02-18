<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;

class AddOrderQuantityForm extends Model
{
    public $id;
    public $doing;

    protected $_order;
    protected $_final_doing;

    public function rules()
    {
        return [
            [['id', 'doing'], 'required'],
            ['id', 'validateOrder'],
            ['doing', 'number'],
            ['doing', 'validateQuantity']
        ];
    }

    public function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }

    public function getFinalDoing()
    {
        $order = $this->getOrder();
        if (!$this->_final_doing) {
            $this->_final_doing = $order->doing_unit + $this->doing;
        }
        return $this->_final_doing;
    }


    public function validateOrder($attribute, $params = []) 
    {
        $order = $this->getOrder();
        if (!$order) return $this->addError($attribute, 'Đơn hàng không tồn tại');
        if (!in_array($order->status, [
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL
        ])) return $this->addError($attribute, sprintf('Không thể xử lý đơn hàng trong trạng thái %s', $order->status));
        
    }

    public function validateQuantity($attribute, $params = [])
    {
        $order = $this->getOrder();
        if ($this->getFinalDoing() > $order->quantity) {
            $this->addError($attribute, 'Số lượng nạp vượt quá yêu cầu');
        }
    }

    public function add()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order = $this->getOrder();
            $order->doing_unit = $this->getFinalDoing();
            $order->save();

            $supplier = $order->supplier;
            if ($supplier && !$supplier->isRequest()) {
                $supplier->doing += $this->doing;
                $supplier->save();
            }
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
}
