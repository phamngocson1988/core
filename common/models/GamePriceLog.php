<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class GamePriceLog extends ActiveRecord
{
	public function behaviors()
	{
	    return [
	        [
	            'class' => BlameableBehavior::className(),
	            'createdByAttribute' => 'updated_by',
	            'updatedByAttribute' => false,
	        ],
	        [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'updated_at',
	            'updatedAtAttribute' => false,
	            'value' => date('Y-m-d H:i:s')
	        ],
	    ];
	}

	public static function tableName()
    {
        return '{{%game_price_log}}';
    }

    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}