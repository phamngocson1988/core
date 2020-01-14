<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use supplier\models\SupplierWallet;
use supplier\models\User;

class FetchWalletForm extends Model
{
    public $id;
    public $supplier_id;
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
        $command = SupplierWallet::find();
        if ($this->id) {
            $command->andWhere(['id' => $this->id]);
        }
        if ($this->supplier_id) {
            $command->andWhere(['supplier_id' => $this->supplier_id]);
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
        $this->_command = $command;

    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

}
