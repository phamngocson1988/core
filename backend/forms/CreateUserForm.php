<?php

namespace backend\forms;

use Yii;
use common\forms\SignupForm as BaseSignupForm;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;

class CreateUserForm extends BaseSignupForm
{
	public $name;
    public $username;
    public $email;
    public $password;
	public $role;

	public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['username', 'password'], function ($attribute, $params) {
                if (preg_match('/\s+/',$this->$attribute)) {
                     $this->addError($attribute, Yii::t('app', 'no_white_space_allowed')); //No white spaces allowed!
                }
            }],

            ['role', 'trim']
        ];
    }

	public function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $names = ArrayHelper::map($roles, 'name', 'description');
        return $names;
    }
	
	public function create()
	{
		$connection = Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			if (!$this->validate()) {
	            return false;
	        }
	        
	        $user = new User();
	        $user->name = $this->name;        
	        $user->username = $this->username;
	        $user->email = $this->email;
	        $user->setPassword($this->password);
	        $user->generateAuthKey();
	        $user->save();
	        
			if ($this->role && ($user instanceof \common\models\User)) {
				$form = new AssignRoleForm(['user_id' => $user->id, 'role' => $this->role, 'scenario' => AssignRoleForm::SCENARIO_ADD]);
				$form->save();
			}	
			$transaction->commit();
			$this->sendEmail();
            Yii::$app->syslog->log('create_user', 'create new user', $user);
			return $user;
		} catch (\Exception $e) {
			$transaction->rollBack();
			$this->addError('username', $e->getMessage());
			return false;
		}
	}

	public function sendEmail()
    {
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        $email = $this->email;
        return Yii::$app->mailer->compose('invite_user', ['mail' => $this])
            ->setTo($email)
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setSubject("[Kinggems][Invitation email] Bạn nhận được lời mời từ " . Yii::$app->name)
            ->setTextBody('Bạn nhận được lời mời làm thành viên quản trị từ kinggems.us')
            ->send();
    }
}
