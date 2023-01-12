<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CustomerTrackerActionLog;

class CreateCustomerContactLogForm extends Model
{
    public $lead_tracker_id;
    public $reason;
    public $content;
    public $plan;

    public function rules()
    {
        return [
            [['lead_tracker_id', 'reason', 'content', 'plan'], 'trim'],
            [['lead_tracker_id', 'reason', 'content', 'plan'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $log = new CustomerTrackerActionLog();
        $log->lead_tracker_id = $this->lead_tracker_id;
        $log->reason = $this->reason;
        $log->content = $this->content;
        $log->plan = $this->plan;
        return $log->save();
    }
}
