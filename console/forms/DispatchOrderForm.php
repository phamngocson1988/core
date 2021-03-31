<?php

namespace console\forms;

use Yii;
use common\models\Order;
use common\models\OrderSupplier;
use common\models\Game;
use common\models\SupplierGame;
use yii\helpers\ArrayHelper;

class DispatchOrderForm extends ActionForm
{
    public $id;
    protected $_order; // Order
    protected $_suppliers;

    public function rules() 
    {
        return [
            ['id', 'required'],
            ['id', 'validateOrder'],

        ];
    }

    public function validateOrder($attribute, $params = [])
    {
        $order = $this->getOrder();
        // Check whether this order exist.
        if (!$order) {
            return $this->addError($attribute, 'Order is not exist');
        }
        // Check order status
        $validStatus = in_array($order->status, [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL,
        ]);
        if (!$validStatus) {
            return $this->addError($attribute, sprintf("Đơn hàng đang ở trạng thái %s nên không thể gửi NCC xử lý", $order->status));
        }
        // Check whether this order has supplier
        $processSupplier = OrderSupplier::find()->where([
            'order_id' => $order->id,
            'status' => [
                OrderSupplier::STATUS_REQUEST,
                OrderSupplier::STATUS_APPROVE,
                OrderSupplier::STATUS_PROCESSING,
            ]
        ])->one();
        if ($processSupplier) {
            return $this->addError($attribute, sprintf("Order đã có nhà cung cấp (%s) xử lý", $processSupplier->supplier_id));
        }

        $suppliers = $this->getSuppliers();
        if (!count($suppliers)) {
            return $this->addError($attribute, 'There is no suppliers register this shop game');
        }
    }
    /**
     * TODO: 
     * - Validate
     * - Fetch suppliers
     * - Calculate piority to assign
     */

    public function dispatch() 
    {
        if (!$this->validate()) return false;

        $order = $this->getOrder();
        $order->log('PPTD Start');
        $suppliers = $this->getSuppliers();
        $order->log(json_encode($suppliers));
        foreach ($suppliers as $supplier) {
            $assignForm = new AssignOrderSupplierForm([
                'order_id' => $this->id,
                'supplier_id' => $supplier['supplier_id']
            ]);
            if ($assignForm->assign()) {
                $order->log(sprintf('Đơn hàng được PPTĐ thành công cho nhà cung cấp %s', $supplier['supplier_id']));
                return true; // break the process
            }
            // assign failure
            if ($assignForm->hasErrors('max_reject')) {
                // Disable auto dispatcher
                $supplierObject = $assignForm->getSupplierGame();
                if ($supplierObject) {
                    $supplierObject->auto_dispatcher = SupplierGame::AUTO_DISPATCHER_OFF;
                    $supplierObject->save();
                }
            }
            $errors = $assignForm->getErrors();
            $order->log('PPTD Fail');
            $order->log(json_encode($errors));
        }
        return true;
    }

    protected function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }

    /**
     * @return [
     *    '1' => ['supplier_id' => 1, 'max_order' => 10, 'num_order' => 4, 'last_speed' => 10]
     *    ...
     * ]
     */
    protected function getSuppliers()
    {
        if (!$this->_suppliers) {
            $order = $this->getOrder();
            // fetch all suppliers
            $suppliers = SupplierGame::find()
            ->select(['supplier_id', 'max_order', 'last_speed'])
            ->asArray()
            ->indexBy('supplier_id')
            ->where([
                'game_id' => $order->game_id,
                'auto_dispatcher' => SupplierGame::AUTO_DISPATCHER_ON,
            ])->all();
            // Count current number of orders for each supplier
            $supplierIds = array_keys($suppliers);
            foreach ($suppliers as $supplierId => $supplier) {
                // Get all order this supplier is supporting for
                $supplierOrders = OrderSupplier::find()
                ->where([
                    'game_id' => $order->game_id,
                    'supplier_id' => $supplierId,
                    'status' => [
                        OrderSupplier::STATUS_REQUEST,
                        OrderSupplier::STATUS_APPROVE,
                        OrderSupplier::STATUS_PROCESSING,]                     
                    ]
                )
                ->with('order')
                ->all();
                // Filter out order is in pending/waiting information (has state data, only happen when Supplier approved order)
                $supplierOrders = array_filter($supplierOrders, function( $supplierOrder ) {
                    if ($supplierOrder->status === OrderSupplier::STATUS_APPROVE) {
                        $order = $supplierOrder->order;
                        return $order->state != Order::STATE_PENDING_INFORMATION; //Filter out order is in pending information
                    }
                    return true;
                });
                $suppliers[$supplierId]['num_order'] = count($supplierOrders);
            }

            // Filter out suppliers which are enough orders
            $suppliers = array_filter($suppliers, function($supplier) {
                return $supplier['num_order'] < $supplier['max_order'];
            });
            // if have no order, go first. Then, check last speed
            if (count($suppliers)) {
                usort($suppliers, function ($a, $b) {
                    if (!$a['num_order']) return -1;
                    if (!$b['num_order']) return 1;
                    return (int)((int)$a['last_speed'] < (int)$b['last_speed']);
                });
            }
            $this->_suppliers = $suppliers;
        }
        return $this->_suppliers;
    }
    
}
