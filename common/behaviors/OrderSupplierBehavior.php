<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\Supplier;

class OrderSupplierBehavior extends AttributeBehavior
{
    public function getSupplier() 
    {
        $owner = $this->owner;
        return $owner->hasOne(Supplier::className(), ['user_id' => 'supplier_id']);
    }

    public function isSupplierAccept()
    {
        $owner = $this->owner;
        return $owner->supplier_id && $owner->supplier_accept == 'Y';
    }
}
