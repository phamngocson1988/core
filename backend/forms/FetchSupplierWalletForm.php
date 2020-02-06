<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\SupplierWallet;

class FetchSupplierWalletForm extends Model
{
    public $supplier_id;
    public $type;
    public $created_at_from;
    public $created_at_to;
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
        $command = SupplierWallet::find();
        if ($this->supplier_id) {
            $command->andWhere(['supplier_id' => $this->supplier_id]);
        }
        if ($this->type) {
            $command->andWhere(['type' => $this->type]);
        }
        if ($this->created_at_from && $this->created_at_to) {
            $command->andWhere(['between', 'created_at', $this->created_at_from, $this->created_at_to]);
        }
        $this->_command = $command;
        return $this->_command;
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = User::findOne($this->supplier_id);
        }
        return $this->_customer;
    }
}
