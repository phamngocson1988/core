<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\LeadTrackerQuestion;
/**
 * EditLeadTrackerQuestionForm is the model behind the contact form.
 */
class EditLeadTrackerQuestionForm extends Model
{
    public $id;
    public $question;
    public $type;
    public $point_yes;
    public $point_no;

    protected $_question;

    public function rules()
    {
        return [
            [['id', 'question', 'type', 'point_yes', 'point_no'], 'trim'],
            [['id', 'question', 'type'], 'required'],
            ['id', 'validateQuestion']
            // [['point_yes', 'point_no'], 'type' => 'number']
        ];
    }

    public function validateQuestion($attribute, $params)
    {
        $question = $this->getQuestion();
        if (!$question) {
            return $this->addError($attribute, 'Lead Tracker Question is not exist');
        }
    }

    protected function getQuestion()
    {
        if (!$this->_question) {
            $this->_question = LeadTrackerQuestion::findOne($this->id);
        }
        return $this->_question;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $leadTracker = $this->getQuestion();
        $leadTracker->question = $this->question;
        $leadTracker->type = $this->type;
        $leadTracker->point_yes = $this->point_yes;
        $leadTracker->point_no = $this->point_no;
        return $leadTracker->save();
    }

    public function fetchTypes() 
    {
        return LeadTrackerQuestion::typeLabels();
    }

    public function loadData()
    {
        $question = $this->getQuestion();
        $this->question = $question->question;
        $this->type = $question->type;
        $this->point_yes = $question->point_yes;
        $this->point_no = $question->point_no;
    }

}
