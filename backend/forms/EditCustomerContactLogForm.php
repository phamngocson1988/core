<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CustomerTrackerActionLog;

class EditCustomerContactLogForm extends Model
{
    public $id;
    public $reason;
    public $content;
    public $plan;

    public function rules()
    {
        return [
            [['id', 'reason', 'content', 'plan'], 'trim'],
            [['id', 'reason', 'content', 'plan'], 'required'],
            ['id', 'validateLog']
        ];
    }

    public function validateLog($attribute, $params)
    {
        $log = $this->getLog();
        if (!$log) {
            return $this->addError($attribute, 'Contact log not exist');
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $log = $this->getLog();
        $log->reason = $this->reason;
        $log->content = $this->content;
        $log->plan = $this->plan;
        return $log->save();
    }

    public function getLog()
    {
        if (!$this->_log) {
          $this->_log = CustomerTrackerActionLog::findOne($this->id);
        }
        return $this->_log;
    }

    public function loadData()
    {
        $log = $this->getLog();
        $this->reason = $log->reason;
        $this->content = $log->content;
        $this->plan = $log->plan;
    }
}
