<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Staff;
use backend\models\Department;
use yii\helpers\ArrayHelper;

/**
 * FetchStaffForm
 */
class FetchStaffForm extends Model
{
    public $q;
    public $gender;
    public $branch;
    public $department;
    private $_command;

    public function rules()
    {
        return [
            [['q', 'gender', 'branch', 'department'], 'trim'],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Staff::find()->select('staff.*');
        $command->with('department');

        if ($this->q) {
            $command->orWhere(['like', 'name', $this->q]);
            $command->orWhere(['like', 'email', $this->q]);
            $command->orWhere(['like', 'phone', $this->q]);
            $command->orWhere(['like', 'address', $this->q]);
        }
        if ((string)$this->gender !== "") {
            $command->andWhere(['gender' => $this->gender]);
        }
        if ((string)$this->department !== "") {
            $command->andWhere(['department_id' => $this->department]);
        }
        if ((string)$this->branch !== "") {
            $command->joinWith('department')
                    ->andWhere(['department.branch' => $this->branch]);
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

    public function getDepartments()
    {
        $departments = Department::find()->all();
        return ArrayHelper::map($departments, 'id', 'name');
    }

    public function getBranches()
    {
        return Department::getBranches();
    }
}
