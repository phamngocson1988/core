<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\LeadTrackerSurveyQuestion;
use common\models\LeadTrackerSurvey;
/**
 * EditLeadTrackerSurveyQuestionForm is the model behind the contact form.
 */
class EditLeadTrackerSurveyQuestionForm extends Model
{
    public $id;
    public $question;
    public $type;
    public $survey_id;
    public $options = [];

    protected $_survey;

    public function rules()
    {
        return [
            [['id', 'question', 'type', 'survey_id'], 'trim'],
            [['id', 'question', 'survey_id'] , 'required'],
            ['id', 'validateSurvey'],
            ['options', 'safe']
        ];
    }

    public function validateSurvey($attribute, $params)
    {
        $survey = $this->getSurvey();
        if (!$survey) {
            return $this->addError($attribute, 'Survey question is not exist');
        }
    }

    public function getSurvey()
    {
        if (!$this->_survey) {
            $this->_survey = LeadTrackerSurveyQuestion::findOne($this->id);
        }
        return $this->_survey;
    }


    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $survey = $this->getSurvey();
        $survey->question = $this->question;
        $survey->survey_id = $this->survey_id;
        $survey->options = json_encode($this->options);
        return $survey->save();
    }

    public function fetchTypes() 
    {
        return LeadTrackerSurveyQuestion::typeLabels();
    }

    public function fetchCustomerTypes() 
    {
        return LeadTrackerSurvey::customerTypeLabels();
    }

    public function fetchSurveys() 
    {
        return ArrayHelper::map(LeadTrackerSurvey::find()->all(), 'id', 'content');
    }

    public function loadData()
    {
        $survey = $this->getSurvey();
        $this->id = $survey->id;
        $this->question = $survey->question;
        $this->type = $survey->type;
        $this->survey_id = $survey->survey_id;
        $this->options = json_decode($survey->options, true);
    }

}
