<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class Payment extends ActiveRecord implements PaymentInterface
{
    CONST STATUS_PENDING = 5;
    const STATUS_CLAIMED = 10;
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
        return '{{%payment}}';
    }

    public static function find()
    {
        return new PaymentQuery(get_called_class());
    }

    public function getId()
    {
        return sprintf("N%s", str_pad($this->id, 8, "0", STR_PAD_LEFT));
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'pending'),
            self::STATUS_CLAIMED => Yii::t('app', 'claimed'),
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

    public function isClaimed()
    {
        return $this->status == self::STATUS_CLAIMED;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
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
}

class PaymentQuery extends ActiveQuery
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
                    case Payment::OBJECT_NAME_ORDER:
                        $class = OrderPayment::className();
                        break;
                    case Payment::OBJECT_NAME_WALLET:
                        $class = WalletPayment::className();
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

interface PaymentInterface
{
    public function getObject();
    public function getObjectKey();
}

class OrderPayment extends Payment implements PaymentInterface
{
    public function getObject()
    {
        return $this->hasOne(Order::className(), ['id' => 'object_key']);
    }

    public function getObjectKey()
    {
        $object = $this->object;
        return $object->getId();
    }
}

class WalletPayment extends Payment implements PaymentInterface
{
    public function getObject()
    {
        return $this->hasOne(PaymentTransaction::className(), ['id' => 'object_key']);
    }

    public function getObjectKey()
    {
        $object = $this->object;
        return $object->getId();
    }
}