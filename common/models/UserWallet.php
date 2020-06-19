<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\ArrayHelper;

class UserWallet extends ActiveRecord
{
	const TYPE_INPUT = "I";
    const TYPE_OUTPUT = "O";

    const STATUS_WAITING = "waiting";
    const STATUS_PENDING = "pending";
    const STATUS_COMPLETED = "completed";

    const REF_ORDER = 'order';

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
        return '{{%user_wallet}}';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function getWalletStatus()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'pending'),
            self::STATUS_COMPLETED => Yii::t('app', 'completed'),
        ];
    }

    public static function getWalletType()
    {
        return [
            self::TYPE_INPUT => Yii::t('app', 'topup'),
            self::TYPE_OUTPUT => Yii::t('app', 'withdraw'),
        ];
    }

    public function getTypeLabel()
    {
        $types = self::getWalletType();
        return ArrayHelper::getValue($types, $this->type, 'Error');
    }
}