<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class PaymentCommitment extends ActiveRecord implements PaymentCommitmentInterface
{
    CONST STATUS_PENDING = 5;
    const STATUS_APPROVED = 10;
    const STATUS_DELETED = 1;

    const PAYMENTTYPE_ONLINE = 'online';
    const PAYMENTTYPE_OFFLINE = 'offline';

    const OBJECT_NAME_ORDER = 'order';
    const OBJECT_NAME_WALLET = 'wallet';

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
        return '{{%payment_commitment}}';
    }

    public static function find()
    {
        return new PaymentCommitmentQuery(get_called_class());
    }

    public function getId()
    {
        return sprintf("N%s", str_pad($this->id, 8, "0", STR_PAD_LEFT));
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'pending'),
            self::STATUS_APPROVED => Yii::t('app', 'approved'),
            self::STATUS_DELETED => Yii::t('app', 'deleted'),
        ];
    }

    public static function getPaymentType()
    {
        return [
            self::PAYMENTTYPE_ONLINE => 'Online',
            self::PAYMENTTYPE_OFFLINE => 'Offline',
        ];
    }

    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function isObjectOrder()
    {
        return $this->object_name == static::OBJECT_NAME_ORDER;
    }

    public function isObjectWallet()
    {
        return $this->object_name == static::OBJECT_NAME_WALLET;
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getConfirmer()
    {
        return $this->hasOne(User::className(), ['id' => 'confirmed_by']);
    }

    public function getObject() 
    {
        return null;
    }

    public function getObjectKey()
    {
        return '';
    }

    public function getReality()
    {
        return $this->hasOne(PaymentReality::className(), ['id' => 'payment_reality_id']);
    }

    public function getStatusName()
    {
        $list = static::getStatusList();
        return ArrayHelper::getValue($list, $this->status, '');
    }
}

class PaymentCommitmentQuery extends ActiveQuery
{
    protected function createModels($rows)
    {
        if ($this->asArray) {
            return $rows;
        } else {
            $models = [];
            /* @var $class ActiveRecord */
            $class = $this->modelClass;
            foreach ($rows as $row) {
                $object = ArrayHelper::getValue($row, 'object_name');
                switch ($object) {
                    case PaymentCommitment::OBJECT_NAME_ORDER:
                        $class = PaymentCommitmentOrder::className();
                        break;
                    case PaymentCommitment::OBJECT_NAME_WALLET:
                        $class = PaymentCommitmentWallet::className();
                        break;
                    default:
                        $class = $this->modelClass;
                        break;
                }
                $model = $class::instantiate($row);
                $modelClass = get_class($model);
                $modelClass::populateRecord($model, $row);
                $models[] = $model;
            }
            return $models;
        }
    }
}