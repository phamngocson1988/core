<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\Supplier;
use common\models\OrderSupplier;

class OrderSupplierBehavior extends AttributeBehavior
{
    // public function getSupplier() 
    // {
    //     $owner = $this->owner;
    //     return $owner->hasOne(Supplier::className(), ['user_id' => 'supplier_id']);
    // }

    // public function isSupplierAccept()
    // {
    //     $owner = $this->owner;
    //     return $owner->supplier_id && $owner->supplier_accept == 'Y';
    // }

    // public function getWaitingApproveTime()
    // {
    //     // $owner = $this->owner;
    //     // if (!$owner->supplier_assign_time) return 0;
    //     // $accept = ($owner->supplier_accept_time) ? $owner->supplier_accept_time : date('Y-m-d H:i:s');
    //     // return strtotime($accept) - strtotime($owner->supplier_assign_time);
    // }
    public function getSuppliers()
    {
        $owner = $this->owner;
        return $owner->hasMany(OrderSupplier::className(), ['order_id' => 'id']);
    }

    public function getSupplier()
    {
        $owner = $this->owner;
        $supplierTable = OrderSupplier::tableName();
        return $owner->hasOne(OrderSupplier::className(), ['order_id' => 'id'])
        ->andOnCondition(["IN", "{$supplierTable}.status", [OrderSupplier::STATUS_APPROVE, OrderSupplier::STATUS_REQUEST]]);
    }
}
