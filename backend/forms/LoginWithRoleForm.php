<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;
use common\models\LoginLog;
use common\models\UserRole;

/**
 * Login form
 */
class LoginWithRoleForm extends Model
{
    public $username;
    public $password;
    public $role;
    public $rememberMe = false;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $roles = $this->getRoles();
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['username', 'validateRole'],
            ['role', 'required', 'message' => 'Hãy chọn vai trò đăng nhập'],
            ['role', 'in', 'range' => array_keys($roles), 'message' => 'Vai trò không hợp lệ'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'rememberMe' => 'Nhớ đăng nhập',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function validateRole($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $count = UserRole::find()->where(['user_id' => $user->id])->count();
            if (!$count) {
                $this->addError($attribute, 'Bạn chưa có vai trò nào trong hệ thống');
            }
        }
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $auth = Yii::$app->authManager; 
            $user = $this->getUser();
            $auth->revokeAll($user->id);
            $role = $auth->getRole($this->role);
            $auth->assign($role, $user->id);
            $result = Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function getRoles()
    {
        $fixRoles = Yii::$app->user->fixRoles;
        $auth = Yii::$app->authManager; 
        $roles = [];
        foreach ($auth->getRoles() as $role) {
            if (!in_array($role->name, $fixRoles)) {
                $roles[$role->name] = $role->description;
            }
        }
        return $roles;
    }
}
