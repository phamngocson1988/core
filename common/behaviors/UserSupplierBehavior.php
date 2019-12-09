<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\SupplierGame;

class UserSupplierBehavior extends AttributeBehavior
{
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
}
