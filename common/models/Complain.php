<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Complain extends ActiveRecord
{
	const STATUS_OPEN = 'open';
	const STATUS_RESOLVE = 'resolve';
	const STATUS_REJECT = 'reject';

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
        return '{{%complain}}';
    }

    public function isOpen()
    {
    	return $this->status == self::STATUS_OPEN;
    }

    public function isResolve()
    {
    	return $this->status == self::STATUS_RESOLVE;
    }

    public function isReject()
    {
    	return $this->status == self::STATUS_REJECT;
    }

    public function getOperator()
    {
        return $this->hasOne(Operator::className(), ['id' => 'operator_id']);
    }

    public function getReason()
    {
        return $this->hasOne(ComplainReason::className(), ['id' => 'reason_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getReplies()
    {
        return $this->hasMany(ComplainReply::className(), ['complain_id' => 'id']);
    }
}