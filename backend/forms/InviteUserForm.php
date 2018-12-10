<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;

class InviteUserForm extends Model
{
	public $name;
    public $username;
    public $email;
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

            [['username'], function ($attribute, $params) {
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
	
	public function invite()
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
	        $user->generateAuthKey();
            $user->setPassword(Yii::$app->security->generateRandomString());
            $user->status = User::STATUS_INACTIVE;
	        if (!$user->save()) {
                throw new Exception("Error Processing Request", 1);
            }
	        
			if ($this->role && ($user instanceof \common\models\User)) {
				$form = new AssignRoleForm(['user_id' => $user->id, 'role' => $this->role, 'scenario' => AssignRoleForm::SCENARIO_ADD]);
				$form->save();
			}	
			$transaction->commit();
			$this->sendEmail($user);
            Yii::$app->syslog->log('invite_user', 'invite user', $user);
			return $user;
		} catch (\Exception $e) {
			$transaction->rollBack();
			$this->addError('username', $e->getMessage());
			return false;
		}
	}

	protected function sendEmail($user)
    {
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        $email = $this->email;
        return Yii::$app->mailer->compose('invite_user', ['mail' => $this, 'activate_link' => Url::to(['site/active-user', 'activation_key' => $user->auth_key, 'id' => $user->id])])
            ->setTo($email)
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setSubject("[Kinggems][Invitation email] Bạn nhận được lời mời từ " . Yii::$app->name)
            ->setTextBody('Bạn nhận được lời mời làm thành viên quản trị từ kinggems.us')
            ->send();
    }
}
