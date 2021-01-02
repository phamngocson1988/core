<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\UserBadge;
use common\models\Country;

class EditProfileForm extends Model
{
    public $firstname;
    public $lastname;
    public $country;
    public $gender;
    public $birthday;

    public function rules()
    {
        return [
            ['firstname', 'trim'],
            ['firstname', 'string', 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'string', 'max' => 255],

            ['country', 'trim'],
            ['gender', 'trim'],

            ['birthday', 'safe'],
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
        $user->country = $this->country;
        $user->gender = $this->gender;
        $user->birthday = $this->birthday;
        if (!$user->hasBadge(UserBadge::BADGE_PROFILE)) {
            $user->addBadge(UserBadge::BADGE_PROFILE, $user->id, 'Complete Profile');
            $user->plusPoint(100, 'Complete Profile');
        }
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
        $this->gender = $user->gender;
        $this->country = $user->country;
        $this->birthday = $user->birthday;
    }

    public function fetchCountry()
    {
        $models = Country::fetchAll();
        return ArrayHelper::map($models, 'country_code', 'country_name');
    }

    public function fetchGender()
    {
        return User::getUserGender();
    }
}
