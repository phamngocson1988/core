<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\LeadTracker;

/**
 * ConvertLeadTrackerToCustomerForm
 */
class ConvertLeadTrackerToCustomerForm extends Model
{
    public $id;

    protected $_leadTracker;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'validateLeadTracker']
        ];
    }

    public function validateLeadTracker($attribute, $params)
    {
        $leadTracker = $this->getLeadTracker();
        if (!$leadTracker) {
            return $this->addError($attribute, 'Lead Tracker not exist');
        }

        $service = $this->getService($leadTracker);
        if (!$service->validate()) {
            return $this->addError($attribute, 'Cannot convert this tracker to customer');
        }
    }

    public function getLeadTracker()
    {
        if (!$this->_leadTracker) {
            $this->_leadTracker = LeadTracker::findOne($this->id);
        }
        return $this->_leadTracker;
    }

    protected function getService($leadTracker)
    {
        return new CreateCustomerForm([
          'name' => $leadTracker->name ? $leadTracker->name : $leadTracker->email,
          'username' => $leadTracker->email,
          'email' => $leadTracker->email,
          'country_code' => $leadTracker->country_code,
          'phone' => $leadTracker->phone,
          'password' => Yii::$app->security->generateRandomString(8),
          'saler_id' => $leadTracker->saler_id,
          'status' => User::STATUS_INACTIVE,
          'send_mail' => true
        ]);
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function convert()
    {
        if (!$this->validate()) {
            return null;
        }
        $leadTracker = $this->getLeadTracker();
        $password = Yii::$app->security->generateRandomString(8);
        $service = $this->getService($leadTracker);
        $service->password = $password;
        return $service->create();
    }
}
