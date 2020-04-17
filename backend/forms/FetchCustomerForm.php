<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use backend\models\User;

class FetchCustomerForm extends Model
{
    public $q;
    public $manager_id;

    private $_command;

    protected function createCommand()
    {
        $command = Customer::find();
        if ($this->q) {
            $command->orWhere(['like', 'name', $this->q]);
            $command->orWhere(['like', 'short_name', $this->q]);
            $command->orWhere(['like', 'phone', $this->q]);
        }
        if ($this->manager_id) {
            $command->andWhere(['manager_id' => $this->manager_id]);
        }
        // die($command->createCommand()->getRawSql());
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchManager()
    {
        $users = User::find()->all();
        return ArrayHelper::map($users, 'id', 'name');
    }
}
