<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

class UserAffiliate extends ActiveRecord
{
    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;

    public $default_duration = 30; //days

	public static function tableName()
    {
        return '{{%user_affiliate}}';
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed'
        ];
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

    public function getStatusLabel()
    {
        $list = self::getStatusList();
        return ArrayHelper::getValue($list, $this->status);
    }

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        $duration = Yii::$app->settings->get('AffiliateProgramForm', 'duration', $this->default_duration);
        $today = new \DateTime();
        $createdDate = new \DateTime($this->created_at);
        return $today->diff($createdDate)->days < $duration && !$this->isCompleted();
    }

    public function isReady()
    {
        $duration = Yii::$app->settings->get('AffiliateProgramForm', 'duration', $this->default_duration);
        $today = new \DateTime();
        $createdDate = new \DateTime($this->created_at);
        return $today->diff($createdDate)->days >= $duration && !$this->isCompleted();
    }

    public function getReadyDate($format = 'Y-m-d')
    {
        $duration = Yii::$app->settings->get('AffiliateProgramForm', 'duration', $this->default_duration);
        $createdDate = new \DateTime($this->created_at);
        return $createdDate->add(new \DateInterval(sprintf("P%sD", $duration)))->format($format);
    }
}