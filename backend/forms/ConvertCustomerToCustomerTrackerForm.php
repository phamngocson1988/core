<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\CustomerTracker;
use backend\models\LeadTracker;

/**
 * ConvertCustomerToCustomerTrackerForm
 */
class ConvertCustomerToCustomerTrackerForm extends Model
{
    /**user_id */
    public $id;

    public $lead_tracker_id;

    protected $_user;
    protected $_leadTracker;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'validateUser'],
            ['lead_tracker_id', 'safe']
        ];
    }

    public function validateUser($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->addError($attribute, 'User not exist');
        }

        $customerTracker = CustomerTracker::find()->where(['user_id' => $this->id])->exists();
        if ($customerTracker) {
            return $this->addError($attribute, 'User was converted to customer tracker');
        }
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->id);
        }
        return $this->_user;
    }

    public function getLeadTracker()
    {
        if (!$this->_leadTracker && $this->lead_tracker_id) {
            $this->_leadTracker = LeadTracker::findOne($this->lead_tracker_id);
        }
        return $this->_leadTracker;
    }

    public function setLeadTracker($leadTracker)
    {
        $this->_leadTracker = $leadTracker;
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function convert()
    {
        if (!$this->validate()) {
            return false;
        }
        $user = $this->getUser();
        $reseller = $user->reseller;
        $leadTracker = $this->getLeadTracker();

        if (!$leadTracker) {
            $createForm = new \backend\forms\CreateLeadTrackerForm([
              'name' => $user->getName(),
              'saler_id' => $reseller ? $reseller->manager_id : $user->saler_id,
              'country_code' => $user->country_code,
              'phone' => $user->phone,
              'email' => $user->email,
            ]);
            $createForm->setScenario(\backend\forms\CreateLeadTrackerForm::SCENARIO_CONVERT);

            $leadTracker = $createForm->save();
            if (!$leadTracker) {
              $errors = $createForm->getFirstErrors();
              $this->addError('id', reset($errors));
              return false;
            }
        }
        $leadTracker->user_id = $this->id;
        $leadTracker->converted_at = date('Y-m-d H:i:s');
        $leadTracker->converted_by = Yii::$app->user->id;
        $leadTracker->registered_at = $user->created_at;
        if ($leadTracker->save()) {
            Yii::$app->queue->push(new \common\queue\RunCustomerTrackerPerformanceJob(['id' => $leadTracker->id]));
        } else {
            $this->addError('id', 'some thing wrong');
            return false;
        }
        return true;
    }
}
