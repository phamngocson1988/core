<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\Country;
use website\models\Game;
use website\models\UserFavorite;

class UpdateSecureProfileForm extends Model
{
    public $firstname;
    public $lastname;
    public $phone;
    public $country_code;
    public $favourite = [];
    protected $_user;

    public function rules()
    {
        return [
            ['firstname', 'required'],
            ['lastname', 'required'],
            [['favourite', 'phone', 'country_code'], 'safe']
        ];
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
            $user->phone = $this->phone;
            $user->country_code = $this->country_code;
            $user->save();

            // Game Favorite
            UserFavorite::deleteAll(['user_id' => $user->id]);
            foreach ((array)$this->favourite as $gameId) {
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

