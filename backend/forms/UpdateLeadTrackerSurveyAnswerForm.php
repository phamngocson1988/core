<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CustomerTracker;
use common\models\LeadTrackerSurveyQuestion;
use common\models\LeadTrackerSurvey;
use common\models\LeadTrackerSurveyAnswer;

class UpdateLeadTrackerSurveyAnswerForm extends Model
{
    public $lead_tracker_id;
    public $survey_id;
    public $question_id;
    public $answer;

    protected $_leadTracker;
    protected $_question;

    public function rules()
    {
        return [
            [['lead_tracker_id', 'survey_id', 'question_id', 'answer'], 'trim'],
            [['lead_tracker_id', 'survey_id', 'question_id'], 'required'],
            ['lead_tracker_id', 'validateLeadTracker'],
            ['question_id', 'validateQuestion']
        ];
    }

    public function validateLeadTracker($attribute, $params) 
    {
        $customerTracker = $this->getCustomerTracker();
        if (!$customerTracker) {
            return $this->addError($attribute, 'Customer tracker is not exist');
        }
    }

    protected function getCustomerTracker()
    {
        if (!$this->_leadTracker) {
          $this->_leadTracker = CustomerTracker::findOne($this->lead_tracker_id);
        }
        return $this->_leadTracker;
    }

    public function validateQuestion($attribute, $params) 
    {
      $question = $this->getQuestion();
      if (!$question) {
          return $this->addError($attribute, 'Question is not exist');
      }
      if ($question->survey_id != $this->survey_id) {
        return $this->addError($attribute, 'Question and Survey is not match');
      }
    }

    protected function getQuestion()
    {
        if (!$this->_question) {
          $this->_question = LeadTrackerSurveyQuestion::findOne($this->question_id);
        }
        return $this->_question;
    }


    public function save()
    {
        $answer = LeadTrackerSurveyAnswer::find()->where([
            'lead_tracker_id' => $this->lead_tracker_id,
            'survey_id' => $this->survey_id,
            'question_id' => $this->question_id,
        ])->one();
        if (!$answer) {
          $answer = new LeadTrackerSurveyAnswer();
        }
        $answer->lead_tracker_id = $this->lead_tracker_id;
        $answer->survey_id = $this->survey_id;
        $answer->question_id = $this->question_id;
        $answer->answer = $this->answer;
        return $answer->save();
    }
}
