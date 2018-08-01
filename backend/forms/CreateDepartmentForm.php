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
    public $address;
    public $phone;
    public $parent_id;

    public function rules()
    {
        return [
            [['name', 'address', 'phone', 'parent_id'], 'trim'],
            ['name', 'required'],
            ['parent_id', 'default', 'value' => '0',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'name'),
            'phone' => Yii::t('app', 'contact_phone'),
            'address' => Yii::t('app', 'address'),
            'parent_id' => Yii::t('app', 'department_parent'),
        ];
    }

    public function create()
    {
        if ($this->validate()) {
            $department = new Department();
            $department->name = $this->name;
            $department->address = $this->address;
            $department->phone = $this->phone;
            $department->parent_id = (int)$this->parent_id;
            return $department->save();
        }
        return false;
    }

    public function fetchParents()
    {
        $departments = Department::find()->where(['parent_id' => 0])->all();
        $departments = ArrayHelper::map($departments, 'id', 'name');
        return $departments;
    }
}
