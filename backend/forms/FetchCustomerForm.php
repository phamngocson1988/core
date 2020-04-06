<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Customer;

class FetchCustomerForm extends Model
{
    public $q;

    private $_command;

    public function rules()
    {
        return [
            ['q', 'trim']
        ];
    }

    protected function createCommand()
    {
        $command = Customer::find();
        if ($this->q) {
            $command->orWhere(['like', 'name', $this->q]);
            $command->orWhere(['like', 'short_name', $this->q]);
            $command->orWhere(['like', 'phone', $this->q]);
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
}
