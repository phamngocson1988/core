<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\LeadTrackerSurvey;
/**
 * CreateLeadTrackerSurveyForm is the model behind the contact form.
 */
class CreateLeadTrackerSurveyForm extends Model
{
    public $question;
    public $type;
    public $options = [['id' => 1, 'value' => 'Option 1']];

    public function rules()
    {
        return [
            [['question', 'type'], 'trim'],
            [['question', 'type'], 'required'],
            ['options', 'safe']
        ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $leadTracker = new LeadTrackerSurvey();
        $leadTracker->question = $this->question;
        $leadTracker->type = $this->type;
        $leadTracker->options = $this->options;
        return $leadTracker->save();
    }

    public function fetchTypes() 
    {
        return LeadTrackerSurvey::typeLabels();
    }

}
