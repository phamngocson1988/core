<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

class LeadTrackerSurveyQuestion extends ActiveRecord
{
    const TYPE_TEXT = 'text';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_SELECT = 'select';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lead_tracker_survey_question}}';
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
            self::TYPE_SELECT => 'Select'
        ];
    }

    public function getAnswer($answers)
    {
        if (!in_array($this->type, [self::TYPE_TEXT, self::TYPE_TEXTAREA])) {
            $options = json_decode($this->options, true);
            $result = array_map(function($answer) use ($options) {
                return ArrayHelper::getValue($options, $answer, null);
            }, (array)$answers);
            $result = array_filter($result);
            return implode(", ", $result);
        }
        return $answers;
    }
}