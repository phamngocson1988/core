<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class UserRefer extends ActiveRecord
{
    const STATUS_SENT = 'sent';
    const STATUS_CREATED = 'created';
    const STATUS_ACTIVATED = 'activated';
    const STATUS_PAYMENT = 'payment';
    const STATUS_INVALID = 'invalid';
    const STATUS_COMPLETED = 'completed';

    public $duration = 604800; // (s) = 7x24x60x60 (7 days)

	public static function tableName()
    {
        return '{{%user_refer}}';
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
        ];
    }

    public function checkExpired($date = null)
    {
        $now = ($date) ? strtotime($date) : strtotime('now');
        $start = strtotime($this->created_at);
        return ($now - $start) <= $this->duration; // within 7 days
    }
}