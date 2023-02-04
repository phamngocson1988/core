<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\LeadTrackerSurvey;
use common\models\LeadTrackerSurveyQuestion;
/**
 * CreateLeadTrackerSurveyForm is the model behind the contact form.
 */
class CreateLeadTrackerSurveyForm extends Model
{
    public $content;
    public $customer_type;

    public function rules()
    {
        return [
            [['content', 'customer_type'], 'trim'],
            [['content', 'customer_type'], 'required'],
        ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $survey = new LeadTrackerSurvey();
        $survey->content = $this->content;
        $survey->customer_type = $this->customer_type;
        return $survey->save();
    }

    public function fetchCustomerTypes() 
    {
        return LeadTrackerSurvey::customerTypeLabels();
    }
}
