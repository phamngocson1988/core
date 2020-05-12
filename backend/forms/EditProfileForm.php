<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;
use common\models\Country;

class EditProfileForm extends Model
{
    public $firstname;
    public $lastname;
    public $country;
    public $gender;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['firstname', 'trim'],
            ['firstname', 'string', 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'string', 'max' => 255],

            ['country', 'trim'],
            ['country', 'string', 'max' => 64],

            ['gender', 'trim'],
            ['gender', 'string'],
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
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->country = $this->country;
        $user->gender = $this->gender;
        return $user->save() ? $user : null;
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
        $this->country = $user->country;
        $this->gender = $user->gender;
    }

    public function fetchGender()
    {
        return User::getUserGender();
    }

    public function getId()
    {
        $user = $this->getUser();
        return $user->id;
    }

    public function fetchCountry()
    {
        $countries = Country::fetchAll();
        return ArrayHelper::map($countries, 'country_code', 'country_name');
    }
}
