<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

class LeadTrackerSurvey extends ActiveRecord
{
    const CUSTOMER_TYPE_NORMAL = 'normal';
    const CUSTOMER_TYPE_POTENTIAL = 'potential';
    const CUSTOMER_TYPE_LOYALTY = 'loyalty';
    const CUSTOMER_TYPE_DANGEROUS = 'dangerous';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lead_tracker_survey}}';
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

    public function getCustomerTypeLabel()
    {
        return ArrayHelper::getValue(self::customerTypeLabels(), $this->customer_type, '-');
    }

    public static function customerTypeLabels()
    {
        return [
            self::CUSTOMER_TYPE_NORMAL => 'Normal',
            self::CUSTOMER_TYPE_POTENTIAL => 'Potential',
            self::CUSTOMER_TYPE_LOYALTY => 'Loyalty',
            self::CUSTOMER_TYPE_DANGEROUS => 'Dangerous',
        ];
    }

    public function getQuestions()
    {
        return $this->hasMany(LeadTrackerSurveyQuestion::className(), ['survey_id' => 'id']);
    }

    public function getTotalQuestion()
    {
        return count($this->questions);
    }
}