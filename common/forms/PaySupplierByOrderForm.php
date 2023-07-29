<?php
namespace common\forms;

use Yii;
use common\models\Order;
use common\models\OrderSupplier;
use common\models\Supplier;

class PaySupplierByOrderForm extends ActionForm
{
    public $order_id; // order

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
        $validOrder = $order->isConfirmedOrder() || $order->isCompletedOrder();
        if (!$validOrder) {
            return $this->addError($attribute, 'Order has not completed yet');
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
        $command = OrderSupplier::find()
            ->where(['order_id' => $order->id])
            ->andWhere(['status' => [OrderSupplier::STATUS_COMPLETED, OrderSupplier::STATUS_CONFIRMED]]);
        $orderSuppliers = $command->all();
        foreach ($orderSuppliers as $orderSupplier) {
            $supplier = Supplier::findOne($orderSupplier->supplier_id);
            if (!$supplier) continue;
            $amount = $orderSupplier->total_price;
            $source = 'order';
            $key = $order->id;
            $description = sprintf("Thanh toán cho đơn hàng #%s", $order->id);
            $supplier->topup($amount, $source, $key, $description);
        }
        
        return true;
    }
}