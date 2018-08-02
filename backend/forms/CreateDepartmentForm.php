<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Department;
use yii\helpers\ArrayHelper;

/**
 * CreateDepartmentForm
 */
class CreateDepartmentForm extends Model
{
    public $name;
    public $branch;
    public $phone;
    public $parent_id;

    public function rules()
    {
        return [
            [['name', 'branch', 'phone', 'parent_id'], 'trim'],
            ['name', 'required'],
            ['parent_id', 'default', 'value' => '0',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'name'),
            'phone' => Yii::t('app', 'contact_phone'),
            'branch' => Yii::t('app', 'branch'),
            'parent_id' => Yii::t('app', 'department_parent'),
        ];
    }

    public function create()
    {
        if ($this->validate()) {
            $department = new Department();
            $department->name = $this->name;
            $department->branch = $this->branch;
            $department->phone = $this->phone;
            $department->parent_id = (int)$this->parent_id;
            return $department->save();
        }
        return false;
    }

    public function fetchParents()
    {
        $departments = Department::find()->all();
        $departments = ArrayHelper::map($departments, 'id', 'name');
        return $departments;
    }

    public function fetchBranches()
    {
        return Department::getBranches();
    }
}
