<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

class UserReseller extends ActiveRecord
{
    const RESELLER_LEVEL_1 = 1;
    const RESELLER_LEVEL_2 = 2;
    const RESELLER_LEVEL_3 = 3;


	public static function tableName()
    {
        return '{{%user_reseller}}';
    }

    public static function primaryKey()
    {
        return ["user_id"];
    } 

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            [
	            'class' => BlameableBehavior::className(),
	            'createdByAttribute' => 'created_by',
	            'updatedByAttribute' => 'updated_by',
	        ],
        ];
    }

    public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getManager()
    {
    	return $this->hasOne(User::className(), ['id' => 'manager_id']);
    }

    public function getLevelUpdater()
    {
    	return $this->hasOne(User::className(), ['id' => 'level_updated_by']);
    }

    public static function getLevelList() 
    {
        return [
            self::RESELLER_LEVEL_1 => 'Gold',
            self::RESELLER_LEVEL_2 => 'Diamond',
            self::RESELLER_LEVEL_3 => 'Platinum',
        ];
    }

    public function getLevelLabel() 
    {
        $list = self::getLevelList();
        return ArrayHelper::getValue($list, $this->level, '');
    }
}