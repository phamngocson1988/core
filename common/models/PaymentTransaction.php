<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\User;

class PaymentTransaction extends ActiveRecord
{
    CONST STATUS_PENDING = "pending";
    const STATUS_COMPLETED = "completed";
    const STATUS_DELETED = "deleted";

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

	public static function tableName()
    {
        return '{{%payment_transaction}}';
    }

    public function getId()
    {
        return "T" . $this->id;
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

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}