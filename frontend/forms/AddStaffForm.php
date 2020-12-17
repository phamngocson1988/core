<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Operator;
use frontend\models\OperatorStaff;

class AddStaffForm extends Model
{
    public $doer_id;
    public $username;
    public $operator_id;
    public $role;

    protected $_doer;
    protected $_victim;
    protected $_operator;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doer_id', 'username', 'operator_id', 'role', 'role'], 'trim'],
            [['doer_id', 'username', 'operator_id', 'role', 'role'], 'required'],
            ['doer_id', 'validateDoer'],
            ['username', 'validateVictim'],
            ['operator_id', 'validateOperator'],
            ['role', 'in', 'range' => [
                OperatorStaff::ROLE_SUBADMIN,
                OperatorStaff::ROLE_MODERATOR,
            ]],
        ];
    }

    public function validateDoer($attribute, $params = [])
    {
        $user = $this->getDoer();
        if (!$user) {
            return $this->addError($attribute, Yii::t('app', 'The one who perform this action is not exist'));
        }
        if (!$user->isOperatorStaffOf($this->operator_id, OperatorStaff::ROLE_ADMIN)) {
            return $this->addError($attribute, Yii::t('app', 'You are not enough permission to perform this action'));
        }
    }

    public function validateVictim($attribute, $params = [])
    {
        $user = $this->getVictim();
        if (!$user) {
            return $this->addError($attribute, Yii::t('app', 'The one who is assigned is not exist'));
        }
        if ($user->isOperatorStaffOf($this->operator_id, $this->role)) {
            return $this->addError($attribute, Yii::t('app', 'This user is already {role} of your operator', ['role' => $this->getRoleName()]));
        }
    }

    public function validateOperator($attribute, $params = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            return $this->addError($attribute, Yii::t('app', 'The operator is not exist'));
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
            $this->_victim = User::find()
            ->where(['status' => User::STATUS_ACTIVE])
            ->andWhere(['OR',
               ['username' => $this->username],
               ['email' => $this->username]
            ])->one();
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

    public function getRoleName()
    {
        switch ($this->role) {
            case OperatorStaff::ROLE_SUBADMIN:
                return Yii::t('app', 'Sub Admin');
            case OperatorStaff::ROLE_MODERATOR:
                return Yii::t('app', 'Moderator');
            default:
                return '';
        }
    }

    public function assign()
    {
    	if (!$this->validate()) {
            return false;
        }
        $victim = $this->getVictim();
        $staff = new OperatorStaff([
            'operator_id' => $this->operator_id,
            'user_id' => $victim->id,
            'role' => $this->role
        ]);
        return $staff->save();
    }
}