<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class SupplierGame extends ActiveRecord
{
    const STATUS_DISABLED = 'disabled';
    const STATUS_ENABLED = 'enabled';

    const AUTO_DISPATCHER_OFF = 0;
    const AUTO_DISPATCHER_ON = 1;

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public static function tableName()
    {
        return '{{%supplier_game}}';
    }

    public static function primaryKey()
    {
        return ["supplier_id", "game_id"];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'supplier_id']);
    }

    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function isEnabled()
    {
        return $this->status == self::STATUS_ENABLED;
    }

    public function isDisabled()
    {
        return $this->status == self::STATUS_DISABLED;
    }

    public function isAvailable()
    {
        return $this->isEnabled() && $this->price;
    }

    public function isAutoDispatcher() 
    {
        return $this->auto_dispatcher === self::AUTO_DISPATCHER_ON;
    }
}
