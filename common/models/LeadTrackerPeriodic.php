<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class LeadTrackerPeriodic extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lead_tracker_periodic}}';
    }

    public static function primaryKey()
    {
        return ['month', 'lead_tracker_id'];
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
}

