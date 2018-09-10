<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Staff;
use backend\models\Department;
use common\components\helpers\FormatConverter;
use yii\helpers\ArrayHelper;

/**
 * CreateStaffForm
 */
class CreateStaffForm extends Model
{
    public $name;
    public $avatar;
    public $email;
    public $phone;
    public $address;
    public $birthday;
    public $gender;
    public $description;
    public $department_id;
    public $start_date;
    public $end_date;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'avatar', 'email', 'phone', 'address', 'birthday', 'gender', 'description', 'department_id', 'start_date', 'end_date'], 'trim'],
            [['name', 'email', 'phone'], 'required'],

            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\Staff', 'message' => Yii::t('app', 'email_exist')],

            ['gender', 'in', 'range' => array_keys(Staff::getStaffGenders())],

            
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'name'),
            'avatar' => Yii::t('app', 'avatar'),
            'email' => Yii::t('app', 'email'),
            'phone' => Yii::t('app', 'contact_phone'),
            'address' => Yii::t('app', 'address'),
            'birthday' => Yii::t('app', 'birthday'),
            'gender' => Yii::t('app', 'gender'),
            'description' => Yii::t('app', 'description'),
            'department_id' => Yii::t('app', 'department'),
            'start_date' => Yii::t('app', 'start_date'),
            'end_date' => Yii::t('app', 'end_date'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return Staff|null the saved model or null if saving fails
     */
    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new Staff();
        $user->name = $this->name;
        $user->avatar = $this->avatar;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->birthday = $this->birthday;
        $user->gender = $this->gender;
        $user->description = $this->description;
        $user->department_id = $this->department_id;
        $user->start_date = $this->start_date;
        $user->end_date = $this->end_date;
        return $user->save() ? $user : null;
    }

    public function getStaffGenders()
    {
        return Staff::getStaffGenders();
    }

    public function getDepartments()
    {
        $departments = Department::find()->all();
        $departments = ArrayHelper::map($departments, 'id', 'name');
        return $departments;
    }
}
