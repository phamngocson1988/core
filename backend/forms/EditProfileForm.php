<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;

class EditProfileForm extends Model
{
    public $username;
    public $name;
    public $phone;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'uniqueUsername'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'uniqueEmail'],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 255],

            ['phone', 'trim'],
        ];
    }

    public function uniqueUsername($attribute, $params = []) 
    {
        $user = $this->getUser();
        if ($user->username == $this->username) return;
        if (User::find()->where(['username' => $this->username])->count() > 0) {
            $this->addError($attribute, 'Tên đăng nhập bị trùng');
        }
    }

    public function uniqueEmail($attribute, $params = []) 
    {
        $user = $this->getUser();
        if ($user->email == $this->email) return;
        if (User::find()->where(['email' => $this->email])->count() > 0) {
            $this->addError($attribute, 'Hộp thư điện tử bị trùng');
        }
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Tên đăng nhập',
            'name' => 'Tên nhân viên',
            'email' => 'Hộp thư điện tử',
            'phone' => 'Số điện thoại',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = $this->getUser();
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->username = $this->username;
        $user->email = $this->email;
        return $user->save() ? $user : null;
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function loadData()
    {
        $user = $this->getUser();
        $this->username = $user->username;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
    }

    public function getId()
    {
        $user = $this->getUser();
        return $user->id;
    }
}
