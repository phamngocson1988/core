<?php
namespace backend\behaviors;

use Yii;
use backend\models\OrderSupplier;

class OrderSupplierBehavior extends \common\behaviors\OrderSupplierBehavior
{
	// New functions
    public function assignSupplier($supplierId) 
    {
        $owner = $this->owner;
        $orderSupplier = new OrderSupplier([
            'order_id' => $owner->id,
            'supplier_id' => $supplierId,
            'quantity' => 0,
            'total_price' => 0,
            'status' => OrderSupplier::STATUS_REQUEST,
            'requested_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getSupplier()
    {
        $owner = $this->owner;
        $supplierTable = OrderSupplier::tableName();
        return $owner->hasOne(OrderSupplier::className(), ['order_id' => 'id'])
        ->andOnCondition(["IN", "{$supplierTable}.status", [OrderSupplier::STATUS_APPROVE, OrderSupplier::STATUS_REQUEST]]);
    }
}