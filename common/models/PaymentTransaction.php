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

    const ID_PREFIX = 'T';

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
        return self::ID_PREFIX . $this->id;
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

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getPaymentData()
    {
        $content = $this->payment_content;
        if ($this->payment_type == 'online') {
            $data = json_decode($this->payment_data, true);
            if ($data && is_array($data)) {
                $params = [];
                foreach ($data as $key => $value) {
                    $newKey = sprintf("{%s}", $key);
                    if (strpos($content, $newKey) !== false) {
                        $params[$newKey] = $value;
                    }
                }
                $content = str_replace(array_keys($params), array_values($params), $content);
            }
        }
        return $content;
    }

    public function delete()
    {
        if ($this->isDeleted()) {
            // If the object is marked as deleted, we will delete it from database
            return parent::delete();
        } else {
            // If it is not marked as deleted, just set status to deleted
            $this->status = self::STATUS_DELETED;
            return $this->save();
        }
        
    }
}