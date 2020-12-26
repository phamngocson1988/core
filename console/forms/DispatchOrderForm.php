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
        if (!$order) {
            return $this->addError($attribute, 'Order is not exist');
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
        $suppliers = $this->getSuppliers();
        foreach ($suppliers as $supplier) {
            $assignForm = new AssignOrderSupplierForm([
                'order_id' => $this->id,
                'supplier_id' => $supplier['supplier_id']
            ]);
            if ($assignForm->validate()) {
                $assignForm->assign();
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
            $errors = $assignForm->getFirstErrors();
            $order->log(reset($errors));
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
                $suppliers[$supplierId]['num_order'] = OrderSupplier::find()->where([
                    'game_id' => $order->game_id,
                    'supplier_id' => $supplierId,
                    'status' => [
                        OrderSupplier::STATUS_REQUEST,
                        OrderSupplier::STATUS_APPROVE,
                        OrderSupplier::STATUS_PROCESSING,]                     
                    ]
                )->count();
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
                    // if ($a['num_order'] == $b['num_order']) {
                        // return (int)$a['last_speed'] < (int)$b['last_speed'];
                    // }
                });
            }
            $this->_suppliers = $suppliers;
        }
        return $this->_suppliers;
    }
    
}
