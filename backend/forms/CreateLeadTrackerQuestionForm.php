<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\LeadTrackerQuestion;
/**
 * CreateLeadTrackerQuestionForm is the model behind the contact form.
 */
class CreateLeadTrackerQuestionForm extends Model
{
    public $question;
    public $type;
    public $point_yes;
    public $point_no;

    public function rules()
    {
        return [
            [['question', 'type', 'point_yes', 'point_no'], 'trim'],
            [['question', 'type'], 'required'],
            // [['point_yes', 'point_no'], 'type' => 'number']
        ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $leadTracker = new LeadTrackerQuestion();
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

}
