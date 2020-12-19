<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Complain;
use frontend\models\User;
use frontend\models\OperatorStaff;
use yii\helpers\ArrayHelper;

class AssignComplainForm extends Model
{
    public $user_id;
    public $complain_id;

    protected $_complain;
    protected $_user;

    public function rules()
    {
        return [
            [['user_id', 'complain_id'], 'required'],
            ['complain_id', 'validateComplain'],
            ['user_id', 'validateUser']
        ];
    }

    public function validateComplain($attribute, $params = [])
    {
        $complain = $this->getComplain();
        if (!$complain) {
            $this->addError($attribute, Yii::t('app', 'Complain is not exist'));
        }
    }

    public function getComplain()
    {
        if (!$this->_complain) {
            $this->_complain = Complain::findOne($this->complain_id);
        }
        return $this->_complain;
    }

    public function validateUser($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $user = $this->getUser();
        $complain = $this->getComplain();
        if (!$user) {
            return $this->addError($attribute, Yii::t('app', 'User is not exist'));
        }
        if (!$user->isOperatorStaffOf($complain->operator_id, OperatorStaff::ROLE_ADMIN) && !$user->isOperatorStaffOf($complain->operator_id, OperatorStaff::ROLE_SUBADMIN)) {
            $this->addError($attribute, Yii::t('app', 'You are not enough permission to perform this action'));
        }
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function assign()
    {
        $complain = $this->getComplain();
        $complain->managed_by = $this->user_id;
        return $complain->save();
    }
}
