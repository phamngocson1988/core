<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Staff;

/**
 * FetchStaffForm
 */
class FetchStaffForm extends Model
{
    public $q;
    public $gender;
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
        $command = Staff::find();

        if ($this->q) {
            $command->orWhere(['like', 'name', $this->q]);
            $command->orWhere(['like', 'email', $this->q]);
            $command->orWhere(['like', 'phone', $this->q]);
            $command->orWhere(['like', 'address', $this->q]);
            $command->orWhere(['like', 'department', $this->q]);
        }
        if ((string)$this->gender !== "") {
            $command->andWhere(['gender' => $this->gender]);
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

    public function getStaffGenders()
    {
        return Staff::getStaffGenders();
    }
}
