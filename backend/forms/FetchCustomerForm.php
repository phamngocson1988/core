<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * FetchCustomerForm
 */
class FetchCustomerForm extends Model
{
    public $q;
    public $status = User::STATUS_ACTIVE;
    private $_command;

    public function rules()
    {
        return [
            [['q', 'status'], 'trim'],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = User::find();

        if ($this->q) {
            $command->orWhere(['like', 'company', $this->q]);
            $command->orWhere(['like', 'name', $this->q]);
            $command->orWhere(['like', 'phone', $this->q]);
            $command->orWhere(['like', 'address', $this->q]);
        }
        if ((string)$this->status !== "") {
            $command->andWhere(['status' => $this->status]);
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
