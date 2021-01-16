<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\Country;
use frontend\models\Game;
use frontend\models\UserFavorite;
// use frontend\forms\VerifyPhoneForm;

class UpdateSecureProfileForm extends Model
{
    public $firstname;
    public $lastname;
    public $phone;
    public $code;
    public $country_code;
    public $favourite = [];
    public $social_facebook;
    public $social_twitter;
    public $social_whatsapp;
    public $social_telegram;
    public $social_wechat;
    public $social_other;
    protected $_user;

    public function rules()
    {
        return [
            ['firstname', 'required'],
            ['lastname', 'required'],
            [['favourite', 'phone', 'code', 'country_code'], 'trim'],
            [['social_facebook', 'social_twitter', 'social_whatsapp', 'social_telegram', 'social_wechat', 'social_other'], 'trim'],
            ['phone', 'validatePhone']
        ];
    }

    public function validatePhone($attribute, $params = [])
    {
        if (!$this->phone) return;
        if (!$this->code) return;
        // $model = new VerifyPhoneForm();
        // $model->phone = $this->phone;
        // $model->code = $this->code;
        // $model->setScenario(VerifyPhoneForm::SCENARIO_VERIFY);
        $user = $this->getUser();
        if ($user->security_pin != $this->code) {
            $this->addError($attribute, 'Security code is invalid');
        }
    }

    public function update()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $user = $this->getUser();
            if (!$user) {
                $this->addError('firstname', 'User is not exist');
                return false;
            }
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            // $user->phone = $this->phone;
            // $user->country_code = $this->country_code;
            $user->social_facebook = $this->social_facebook;
            $user->social_twitter = $this->social_twitter;
            $user->social_telegram = $this->social_telegram;
            $user->social_whatsapp = $this->social_whatsapp;
            $user->social_wechat = $this->social_wechat;
            $user->social_other = $this->social_other;
            $user->save();

            // Game Favorite
            UserFavorite::deleteAll(['user_id' => $user->id]);
            foreach ((array)$this->favourite as $gameId) {
                if (!$gameId) continue;
                $favourite = new UserFavorite();
                $favourite->user_id = $user->id;
                $favourite->game_id = $gameId;
                $favourite->save();
            } 
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = Yii::$app->user->getIdentity();
        }
        return $this->_user;
    }

    public function loadForm()
    {
        $user = $this->getUser();
        $favourite = UserFavorite::find()->where(['user_id' => $user->id])->select(['game_id'])->all();

        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->phone = $user->phone;
        $this->country_code = $user->country_code;
        $this->social_facebook = $user->social_facebook;
        $this->social_twitter = $user->social_twitter;
        $this->social_telegram = $user->social_telegram;
        $this->social_whatsapp = $user->social_whatsapp;
        $this->social_wechat = $user->social_wechat;
        $this->social_other = $user->social_other;
        $this->favourite = ArrayHelper::getColumn($favourite, 'game_id');
    }

    public function listCountries()
    {
        return ArrayHelper::map(Country::fetchAll(), 'country_code', 'country_name');
    }

    public function listCountryAttributes()
    {
        $attrs = [];
        foreach (Country::fetchAll() as $country) {
            $attrs[$country->country_code] = ['data-dialling' => $country->dialling_code];
        }
        return $attrs;
    }

    public function fetchGame()
    {
        $games = Game::find()->where(['status' => Game::STATUS_VISIBLE])->select(['id', 'title'])->all();
        return ArrayHelper::map($games, 'id', 'title');
    }
}

