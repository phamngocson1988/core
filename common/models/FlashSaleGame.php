<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class FlashSaleGame extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%flashsale_game}}';
    }

    public function getGame() 
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }
}
