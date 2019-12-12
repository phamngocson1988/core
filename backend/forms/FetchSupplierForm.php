<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Supplier;

class FetchSupplierForm extends Model
{
    public $user_id;
    public $status;
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
        $supplierTable = Supplier::tableName();
        $command = Supplier::find();
        $command->with('user');
        $command->select(["{$supplierTable}.*"]);
        // $command->leftJoin($userTable, "{$supplierTable}.user_id = {$userTable}.id");

        if ($this->user_id) {
            $command->andWhere(["{$supplierTable}.user_id" =>  $this->user_id]);
        }

        if ($this->status) {
            $command->andWhere(["{$supplierTable}.status" => $this->status]);
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
