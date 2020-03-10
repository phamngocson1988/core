<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $role;
    public $rememberMe = false;

    private $_user;
    protected $_unchangable_roles = ['admin', 'manager'];

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
            ['role', 'required', 'message' => 'Hãy chọn vai trò đăng nhập'],
            ['role', 'in', 'range' => array_keys($roles), 'message' => 'Vai trò không hợp lệ'],
            ['role', 'validateRole'],
        ];
    }

    public function attributeLabels()
    {
        return [
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
        $userRoles = $this->getUserRoles();
        $intersectRoles = array_intersect($this->_unchangable_roles, $userRoles);
        if (!count($intersectRoles)) { // user is not admin/manager
            if (in_array($this->role, $this->_unchangable_roles)) {
                $this->addError($attribute, 'Bạn không được quyền đăng nhập quyền này');
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
            $userRoles = $this->getUserRoles();
            $intersectRoles = array_intersect($this->_unchangable_roles, $userRoles);
            if (!count($intersectRoles)) { // user is not admin/manager
                foreach ($userRoles as $userRoleName) {
                    $userRole = $auth->getRole($userRoleName);
                    $auth->revoke($userRole, $user->id);
                }
                $role = $auth->getRole($this->role);
                $auth->assign($role, $user->id);
            }
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
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
        $auth = Yii::$app->authManager; 
        $roles = $auth->getRoles();
        $list = ArrayHelper::map($roles, 'name', 'description');
        return $list;
    }

    public function getUserRoles()
    {
        $user = $this->getUser();
        $roleNames = Yii::$app->authManager->getRolesByUser($user->id);
        return array_keys($roleNames);
    }
}
