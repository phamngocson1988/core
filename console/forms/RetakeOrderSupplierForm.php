<?php
namespace console\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\OrderSupplier;

class RetakeOrderSupplierForm extends Model
{
    public $order_id;

    protected $_order;
    protected $_supplier;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
            ['order_id', 'validateOrder'],
        ];
    }

    public function getOrder()
    {
        if (!$this->_order) $this->_order = Order::findOne($this->order_id);
        return $this->_order;
    }

    public function getSupplier()
    {
        if (!$this->_supplier) {
            $order = $this->getOrder();
            if ($order) {
                $this->_supplier = $order->supplier;
            }
        }
        return $this->_supplier;
    }

    public function validateOrder($attribute, $params = []) 
    {
        $order = $this->getOrder();
        if (!$order) return $this->addError($attribute, 'Đơn hàng không tồn tại');
        if (!in_array($order->status, [
            Order::STATUS_PENDING, 
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL,
        ])) return $this->addError($attribute, 'Không thể lấy lại đơn hàng từ nhà cung cấp');

        $supplier = $this->getSupplier();
        if (!$supplier) return $this->addError($attribute, 'Đơn hàng này chưa có nhà cung cấp');
    }

    public function retake()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $supplier = $this->getSupplier();
            $supplier->status = OrderSupplier::STATUS_RETAKE;
            $supplier->retaken_at = date('Y-m-d H:i:s');
            $supplier->save();

            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

}
