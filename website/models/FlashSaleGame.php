<?php
namespace website\models;
use Yii;

class FlashSaleGame extends \common\models\FlashSaleGame
{
		public function getGame() 
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }
}
