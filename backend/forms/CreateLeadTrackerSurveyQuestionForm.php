<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\LeadTrackerSurvey;
use common\models\LeadTrackerSurveyQuestion;
/**
 * CreateLeadTrackerSurveyQuestionForm is the model behind the contact form.
 */
class CreateLeadTrackerSurveyQuestionForm extends Model
{
    public $question;
    public $type;
    public $survey_id;
    public $options = [];

    public function rules()
    {
        return [
            [['question', 'type', 'survey_id'], 'trim'],
            [['question', 'type', 'survey_id'], 'required'],
            ['options', 'safe']
        ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $survey = new LeadTrackerSurveyQuestion();
        $survey->question = $this->question;
        $survey->type = $this->type;
        $survey->survey_id = $this->survey_id;
        $survey->options = json_encode($this->options);
        return $survey->save();
    }

    public function fetchTypes() 
    {
        return LeadTrackerSurveyQuestion::typeLabels();
    }

    public function fetchSurveys() 
    {
        return ArrayHelper::map(LeadTrackerSurvey::find()->all(), 'id', 'content');
    }
}
