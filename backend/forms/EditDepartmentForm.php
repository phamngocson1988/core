<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Department;
use yii\helpers\ArrayHelper;

/**
 * EditDepartmentForm
 */
class EditDepartmentForm extends Model
{
    public $id;
    public $name;
    public $branch;
    public $phone;
    public $parent_id;

    protected $_department;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'validateDepartment'],

            [['name', 'branch', 'phone', 'parent_id'], 'trim'],
            ['name', 'required'],
            ['branch', 'required'],
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

    /**
     * Signs user up.
     *
     * @return Department|null the saved model or null if saving fails
     */
    public function save()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $department = $this->getDepartment();
        $department->name = $this->name;
        $department->branch = $this->branch;
        $department->phone = $this->phone;
        $department->parent_id = (int)$this->parent_id;
        return $department->save() ? $department : null;
    }

    public function validateDepartment($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $department = $this->getDepartment();
            if (!$department) {
                $this->addError($attribute, Yii::t('app', 'invalid_department'));
            }
        }
    }

    protected function getDepartment()
    {
        if ($this->_department === null) {
            $this->_department = Department::findOne($this->id);
        }

        return $this->_department;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $department = $this->getDepartment();
        $this->name = $department->name;
        $this->branch = $department->branch;
        $this->phone = $department->phone;
        $this->parent_id = (int)$department->parent_id;
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
