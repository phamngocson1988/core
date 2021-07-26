<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;

class EditProfileForm extends Model
{
    public $name;
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $birthday;
    public $phone;
    public $country_code;
    public $is_verify_email;

    public $social_facebook;
    public $social_twitter;
    public $social_whatsapp;
    public $social_telegram;
    public $social_wechat;
    public $social_other;

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],

            ['name', 'trim'],
            ['name', 'string', 'max' => 255],

            ['firstname', 'trim'],
            ['firstname', 'string', 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'string', 'max' => 255],

            ['birthday', 'trim'],

            [['country_code', 'phone'], 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 7],

            ['is_verify_email', 'safe'],

            [['social_facebook', 'social_twitter', 'social_whatsapp', 'social_telegram', 'social_wechat', 'social_other'], 'trim']
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
        $user->name = $this->name;
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->birthday = $this->birthday;
        $user->phone = $this->phone;
        $user->country_code = $this->country_code;
        $user->social_facebook = $this->social_facebook;
        $user->social_twitter = $this->social_twitter;
        $user->social_whatsapp = $this->social_whatsapp;
        $user->social_telegram = $this->social_telegram;
        $user->social_wechat = $this->social_wechat;
        $user->social_other = $this->social_other;
        return $user->save();
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function loadData()
    {
        $user = $this->getUser();
        $this->name = $user->name;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->birthday = $user->birthday;
        $this->phone = $user->phone;
        $this->country_code = $user->country_code;
        $this->is_verify_email = $user->is_verify_email;
        $this->social_facebook = $user->social_facebook;
        $this->social_twitter = $user->social_twitter;
        $this->social_whatsapp = $user->social_whatsapp;
        $this->social_telegram = $user->social_telegram;
        $this->social_wechat = $user->social_wechat;
        $this->social_other = $user->social_other;
    }
}
