<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

class LeadTrackerSurvey extends ActiveRecord
{
    const TYPE_TEXT = 'text';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';
    const TYPE_TEXTAREA = 'textarea';

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

    public function getTypeLabel()
    {
        return ArrayHelper::getValue(self::typeLabels(), $this->type, '-');
    }

    public static function typeLabels()
    {
        return [
            self::TYPE_TEXT => 'Text',
            self::TYPE_CHECKBOX => 'Check Box',
            self::TYPE_RADIO => 'Radio',
            self::TYPE_TEXTAREA => 'Textarea',
        ];
    }
}