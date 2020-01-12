<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

class Supplier extends ActiveRecord
{
    const STATUS_DISABLED = 'disabled';
    const STATUS_ENABLED = 'enabled';

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
        return '{{%supplier}}';
    }

    public static function primaryKey()
    {
        return ["user_id"];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function isDisabled() 
    {
        return $this->status == self::STATUS_DISABLED;
    }

    public function isEnabled()
    {
        return $this->status == self::STATUS_ENABLED;
    }

    public function getGames()
    {
        return $this->hasMany(SupplierGame::className(), ['supplier_id' => 'user_id']);
    }

    public function getActiveGames()
    {
        $command = $this->getGames();
        $supplierGameTable = SupplierGame::tableName();
        $command->andOnCondition(["{$supplierGameTable}.status" => SupplierGame::STATUS_ENABLED]);
        return $command;
    }

    public function hasGame($gameId) 
    {
        $games = $this->activeGames;
        $gameIds = ArrayHelper::getColumn($games, 'game_id');
        return in_array($gameId, $gameIds);
    }

    public function getBanks()
    {
        return $this->hasMany(SupplierBank::className(), ['supplier_id' => 'user_id']);
    }

}
