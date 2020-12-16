<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Operator;
use frontend\models\OperatorStaff;

class RevokeStaffForm extends Model
{
    public $doer_id;
    public $victim_id;
    public $operator_id;
    public $role;

    protected $_doer;
    protected $_victim;
    protected $_operator;
    protected $_staff;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doer_id', 'victim_id', 'operator_id', 'role'], 'trim'],
            [['doer_id', 'victim_id', 'operator_id', 'role'], 'required'],
            ['doer_id', 'validateDoer'],
            ['victim_id', 'validateVictim'],
            ['operator_id', 'validateOperator'],
            ['role', 'in', 'range' => [
                OperatorStaff::ROLE_ADMIN,
                OperatorStaff::ROLE_SUBADMIN,
                OperatorStaff::ROLE_MODERATOR,
            ]],
            ['role', 'validateStaff'],
        ];
    }

    public function validateDoer($attribute, $params = [])
    {
        $user = $this->getDoer();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'The one who perform this action is not exist'));
        }
        if (!$user->isOperatorStaffOf($this->operator_id, OperatorStaff::ROLE_ADMIN)) {
            $this->addError($attribute, Yii::t('app', 'You are not enough permission to perform this action'));
        }
        if ($this->doer_id == $this->victim_id) {
            $this->addError($attribute, Yii::t('app', 'You cannot revoke yourself'));
        }
    }

    public function validateVictim($attribute, $params = [])
    {
        $user = $this->getVictim();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'The one who is revoked is not exist'));
        }
        if ($user->isOperatorStaffOf($this->operator_id, OperatorStaff::ROLE_ADMIN)) {
            $this->addError($attribute, Yii::t('app', 'You are not enough permission to perform this action'));
        }
    }

    public function validateOperator($attribute, $params = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            $this->addError($attribute, Yii::t('app', 'The operator is not exist'));
        }
    }

    public function validateStaff($attribute, $params = [])
    {
        $staff = $this->getStaff();
        if (!$staff) {
            $this->addError($attribute, Yii::t('app', 'This user is not in this role'));
        }
    }

    public function getDoer()
    {
        if (!$this->_doer) {
            $this->_doer = User::findOne($this->doer_id);
        }
        return $this->_doer;
    }

    public function getVictim()
    {
        if (!$this->_victim) {
            $this->_victim = User::findOne($this->victim_id);
        }
        return $this->_victim;
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->operator_id);
        }
        return $this->_operator;
    }

    public function getStaff()
    {
        if (!$this->_staff) {
            $this->_staff = OperatorStaff::find()->where([
                'user_id' => $this->victim_id,
                'operator_id' => $this->operator_id,
                'role' => $this->role,
            ])->one();
        }
        return $this->_staff;
    }

    public function revoke()
    {
    	if (!$this->validate()) {
            return false;
        }
        $staff = $this->getStaff();
        return $staff->delete();
    }
}