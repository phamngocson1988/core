<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;

class EditProfileForm extends Model
{
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $birthday;
    public $phone;

    public function rules()
    {
        return [
            ['firstname', 'trim'],
            ['firstname', 'string', 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'string', 'max' => 255],

            ['birthday', 'trim'],
            ['phone', 'trim'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        $user = $this->getUser();
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->birthday = $this->birthday;
        $user->phone = $this->phone;
        return $user->save();
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function loadData()
    {
        $user = $this->getUser();
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->birthday = $user->birthday;
        $this->phone = $user->phone;
    }
}
