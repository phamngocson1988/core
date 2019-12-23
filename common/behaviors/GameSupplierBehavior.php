<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\SupplierGame;
use common\models\Supplier;

class GameSupplierBehavior extends AttributeBehavior
{
    public function countSupplier() 
    {
        return $this->getSuppliers()->count();
    }

    public function getSuppliers() 
    {
        $supplierTable = Supplier::tableName();
        $gameTable = SupplierGame::tableName();
        $owner = $this->owner; //Game
        return $owner->hasMany(Supplier::className(), ['user_id' => 'supplier_id'])
        ->andOnCondition(["{$supplierTable}.status" => Supplier::STATUS_ENABLED])
        ->viaTable(SupplierGame::tableName(), ['game_id' => 'id'], function ($query) {
            $query->andWhere(['status' => SupplierGame::STATUS_ENABLED]);
        });
    }
}
