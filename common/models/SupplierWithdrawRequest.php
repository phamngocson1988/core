<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class SupplierWithdrawRequest extends ActiveRecord
{
    const STATUS_REQUEST = "request";
    const STATUS_APPROVE = "approve";
    const STATUS_DONE = "done";
    const STATUS_CANCEL = "cancel";

    public static function tableName()
    {
        return '{{%supplier_withdraw_request}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_REQUEST => "Gửi yêu cầu",
            self::STATUS_APPROVE => "Đã phê duyệt",
            self::STATUS_DONE => "Đã hoàn tất",
            self::STATUS_CANCEL => "Hủy bỏ",
        ];
    }

    public function getSupplier()
    {
    	return $this->hasOne(Supplier::className(), ['user_id' => 'supplier_id']);
    }

    public function getId()
    {
        return 'GD' . $this->id;
    }

    public function isRequest()
    {
    	return $this->status == self::STATUS_REQUEST;
    }

    public function isApprove()
    {
        return $this->status == self::STATUS_APPROVE;
    }

    public function isDone()
    {
        return $this->status == self::STATUS_DONE;
    }

    public function isCancel()
    {
        return $this->status == self::STATUS_CANCEL;
    }
}
