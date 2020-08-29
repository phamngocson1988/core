<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate())  return null;
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_INACTIVE;
        if (!$user->save()) return null;

        $admin = Yii::$app->params['admin_email'];
        $siteName = Yii::$app->name;
        $email = Yii::$app->mailer->compose('signup_mail', [
            'user' => $user,
        ])
        ->setTo($user->email)
        ->setFrom([$admin => $siteName])
        ->setSubject(sprintf('[%s] Verify your email', Yii::$app->name))
        ->setTextBody(sprintf("[%s] Verify your email", Yii::$app->name))
        ->send();

        return $user;
    }
}
