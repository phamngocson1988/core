<?php
namespace website\behaviors;
use yii\behaviors\AttributeBehavior;
use website\models\FlashSale;
use website\models\FlashSaleGame;

class GamePriceBehavior extends AttributeBehavior
{
    // public function getFlashSalePrice() 
    // {
    //     $owner = $this->owner; //Game
    //     $now = date('Y-m-d H:i:s');
    //     $flashSaleTable = FlashSale::tableName();
    //     $flashSaleGameTable = FlashSaleGame::tableName();
    //     return FlashSaleGame::find()
    //     ->innerJoin($flashSaleTable, "{$flashSaleTable}.id = {$flashSaleGameTable}.flashsale_id")
    //     ->where(['<=', "{$flashSaleTable}.start_from", $now])
    //     ->andWhere(['>=', "{$flashSaleTable}.start_to", $now])
    //     ->andWhere(["{$flashSaleGameTable}.game_id" => $owner->id])
    //     ->one();
    // }
}
