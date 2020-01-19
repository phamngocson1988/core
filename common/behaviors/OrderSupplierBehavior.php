<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\Supplier;
use common\models\OrderSupplier;

class OrderSupplierBehavior extends AttributeBehavior
{
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
