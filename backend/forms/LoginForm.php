<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;
use common\models\UserRole;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => 'Bắt buộc nhập'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['username', 'validateRole']
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

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Tên đăng nhập hoặc mật khẩu không đúng');
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

    public function login()
    {
        if ($this->validate()) {
            $auth = Yii::$app->authManager; 
            $user = $this->getUser();
            $auth->revokeAll($user->id);
            $userRoles = UserRole::find()->where(['user_id' => $user->id])->all();
            foreach ($userRoles as $userRole) {
                $role = $auth->getRole($userRole->role);
                if (!$role) continue;
                $auth->assign($role, $user->id);
            }
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
}
