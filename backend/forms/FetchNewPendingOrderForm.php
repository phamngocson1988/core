<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\Game;
use backend\models\User;
use yii\helpers\ArrayHelper;

class FetchNewPendingOrderForm extends Model
{
    public $q;
    public $customer_id;
    public $game_id;
    public $saler_id;

    private $_command;

    public function rules()
    {
        return [
            ['q', 'trim'],
            [['game_id', 'customer_id', 'saler_id'], 'safe'],
        ];
    }
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Order::find();
        $command->where(['status' => Order::STATUS_PENDING]);
        $command->andWhere(['orderteam_id' => null]);
        if ($this->q) {
            $command->andWhere(['OR',
                ['id' => $this->q],
                ['auth_key' =>  $this->q]
            ]);
            $this->_command = $command;
            return;
        }
        if ($this->customer_id) {
            $command->andWhere(['customer_id' => $this->customer_id]);
        }
        if ($this->game_id) {
            $command->andWhere(['game_id' => $this->game_id]);
        }
        if ($this->saler_id) {
            $command->andWhere(['saler_id' => $this->saler_id]);
        }
        $this->_command = $command;

    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchSalers()
    {
        $salerIds = Yii::$app->authManager->getUserIdsByRole('saler');
        $salers = User::findAll($salerIds);
        return ArrayHelper::map($salers, 'id', 'email');
    }

    public function getCustomer()
    {
        if ($this->customer_id) {
            return User::findOne($this->customer_id);
        }
    }

    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }
}
