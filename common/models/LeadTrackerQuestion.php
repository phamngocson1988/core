<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

class LeadTrackerQuestion extends ActiveRecord
{
    const TYPE_LEAD_TARGET = 'lead';
    const TYPE_POTENTIAL_TARGET = 'potential';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lead_tracker_question}}';
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
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function getTypeLabel()
    {
        return ArrayHelper::getValue(self::typeLabels(), $this->type, '-');
    }

    public static function typeLabels()
    {
        return [
            self::TYPE_LEAD_TARGET => 'Lead Target',
            self::TYPE_POTENTIAL_TARGET => 'Potential Target'
        ];
    }
}