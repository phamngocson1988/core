<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\SupplierGame;
use common\models\Supplier;

class UserSupplierBehavior extends AttributeBehavior
{
    public function isSupplier() 
    {
        return $this->getSupplier()->count();
    }

    public function getSupplier() 
    {
        $owner = $this->owner;
        return $owner->hasOne(Supplier::className(), ['user_id' => 'id']);
    }

    public function getSupplierGames()
    {
        $owner = $this->owner;// user
        return $owner->hasMany(SupplierGame::className(), ['supplier_id' => 'id']);
    }

    public function isOwnGame($game_id)
    {
        $owner = $this->owner;
        $command = $this->getSupplierGames();
        $command->where(['game_id' => $game_id]);
        return true && $command->count();
    }

    public function hasSupplierPassword()
    {
        $supplier = $this->supplier;
        if (!$supplier) return false;
        return $supplier->password;
    }
}
