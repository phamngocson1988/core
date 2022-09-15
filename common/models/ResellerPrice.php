<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class ResellerPrice extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%reseller_price}}';
    }

    public static function primaryKey()
    {
        return ["reseller_id", "game_id"];
    } 

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ]
        ];
    }

    public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'reseller_id']);
    }

    public function getGame()
    {
    	return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }
}
