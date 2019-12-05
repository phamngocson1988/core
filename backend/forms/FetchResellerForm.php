<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\UserReseller;
use yii\helpers\ArrayHelper;

class FetchResellerForm extends Model
{
    public $user_id;
    public $phone;
    public $manager_id;
    private $_command;
    protected $_customer;

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    protected function createCommand()
    {
        $userTable = User::tableName();
        $resellerTable = UserReseller::tableName();
        $command = UserReseller::find();
        $command->select(["{$resellerTable}.*"]);
        $command->leftJoin($userTable, "{$resellerTable}.user_id = {$userTable}.id");

        if ($this->user_id) {
            $command->andWhere(["{$resellerTable}.user_id" =>  $this->user_id]);
        }

        if ($this->manager_id) {
            $command->andWhere(["{$resellerTable}.manager_id" =>  $this->manager_id]);
        }

        if ($this->phone) {
            $command->andWhere(["LIKE", "{$userTable}.phone", $this->phone]);
        }
        $this->_command = $command;
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = User::findOne($this->user_id);
        }
        return $this->_customer;
    }
}
