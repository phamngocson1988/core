<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ResellerPrice;
use backend\models\User;
use backend\models\Game;
use yii\helpers\ArrayHelper;

/**
 * CreateResellerPriceForm is the model behind the contact form.
 */
class CreateResellerPriceForm extends Model
{
    public $reseller_id;
    public $game_id;
    public $price;
    public $duration = 3; // days

    protected $_reseller_price;
    protected $_user;
    protected $_game;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reseller_id', 'game_id', 'price'], 'required'],
            ['game_id', 'validateGame'],
            ['reseller_id', 'validateReseller'],
        ];
    }

    public function validateGame($attribute, $params = []) 
    {
        $game = $this->getGame();
        if (!$game) {
            return $this->addError($attribute, 'game_not_found');
        }
    }

    public function getGame() 
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
    }

    public function validateReseller($attribute, $params = []) 
    {
        $user = $this->getReseller();
        if (!$user) {
            return $this->addError($attribute, 'user_not_found');
        }
    }

    public function getReseller() 
    {
        if (!$this->_user) {
            $this->_user = User::findOne([
                'id' => $this->reseller_id,
                'is_reseller' => User::IS_RESELLER
            ]);
        }
        return $this->_user;
    }

    public function getResellerPrice()
    {
        if (!$this->_reseller_price) {
            $this->_reseller_price = ResellerPrice::findOne(['reseller_id' => $this->reseller_id, 'game_id' => $this->game_id]);
        } 
        if (!$this->_reseller_price) {
            $this->_reseller_price = new ResellerPrice();
        }
        return $this->_reseller_price;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $resellerPrice = $this->getResellerPrice();
        $resellerPrice->reseller_id = $this->reseller_id;
        $resellerPrice->game_id = $this->game_id;
        $resellerPrice->price = $this->price;
        $resellerPrice->invalid_at = date('Y-m-d H:i:s', strtotime("+$this->duration days"));
        return $resellerPrice->save();
    }

    public function fetchResellers()
    {
        return ArrayHelper::map(User::find()->select(['id', 'name'])->where(['is_reseller' => User::IS_RESELLER])->all(), 'id', 'name');
    }

    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->select(['id', 'title'])->all(), 'id', 'title');
    }
}
