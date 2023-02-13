<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\LeadTrackerSurveyQuestion;
use common\models\LeadTrackerSurvey;
/**
 * EditLeadTrackerSurveyForm is the model behind the contact form.
 */
class EditLeadTrackerSurveyForm extends Model
{
    public $id;
    public $content;
    public $customer_type;
    public $survey_id;
    public $options = [];

    protected $_survey;

    public function rules()
    {
        return [
            [['id', 'content', 'customer_type'], 'trim'],
            [['id', 'content', 'customer_type'], 'required'],
            ['id', 'validateSurvey'],
        ];
    }

    public function validateSurvey($attribute, $params)
    {
        $survey = $this->getSurvey();
        if (!$survey) {
            return $this->addError($attribute, 'Survey is not exist');
        }
    }

    public function getSurvey()
    {
        if (!$this->_survey) {
            $this->_survey = LeadTrackerSurvey::findOne($this->id);
        }
        return $this->_survey;
    }


    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $survey = $this->getSurvey();
        $survey->content = $this->content;
        $survey->customer_type = $this->customer_type;
        return $survey->save();
    }

    public function fetchTypes() 
    {
        return LeadTrackerSurvey::customerTypeLabels();
    }

    public function fetchQuestions() 
    {
        return LeadTrackerSurveyQuestion::find()->where(['survey_id' => $this->id])->all();
    }

    public function loadData()
    {
        $survey = $this->getSurvey();
        $this->id = $survey->id;
        $this->content = $survey->content;
        $this->customer_type = $survey->customer_type;
    }

    public function fetchCustomerTypes() 
    {
        return LeadTrackerSurvey::customerTypeLabels();
    }
}
