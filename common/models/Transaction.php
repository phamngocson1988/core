<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\User;

class Transaction extends ActiveRecord
{
    CONST STATUS_PENDING = "pending";
    const STATUS_COMPLETED = "completed";

    public function behaviors()
    {
        return [
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
        return '{{%transaction}}';
    }

    public function generateAuthKey()
    {
        $this->auth_key = "TR" . Yii::$app->security->generateRandomString(8);
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'pending'),
            self::STATUS_COMPLETED => Yii::t('app', 'completed'),
        ];
    }
}