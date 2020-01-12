<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\UserWallet;
use backend\models\User;

class FetchWalletForm extends Model
{
    public $id;
    public $user_id;
    public $status;
    public $created_at_from;
    public $created_at_to;
    private $_command;

    protected $_customer;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = UserWallet::find();
        if ($this->id) {
            $command->andWhere(['id' => $this->id]);
        }
        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }
        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }
        if ($this->created_at_from) {
            $command->andWhere(['>=', "created_at", $this->created_at_from]);
        }
        if ($this->created_at_to) {
            $command->andWhere(['<=', "created_at", $this->created_at_to]);
        }
        $command->with('user');
        $this->_command = $command;

    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = User::findOne($this->user_id);
        }
        return $this->_customer;
    }
}
