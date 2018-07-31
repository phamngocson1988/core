<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Staff;

/**
 * EditStaffForm
 */
class EditStaffForm extends Model
{
    public $id;
    public $name;
    public $avatar;
    public $email;
    public $phone;
    public $address;
    public $birthday;
    public $gender;
    public $description;
    public $department;
    public $start_date;
    public $end_date;

    protected $_staff;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'validateStaff'],

            [['name', 'avatar', 'email', 'phone', 'address', 'birthday', 'gender', 'description', 'department', 'start_date', 'end_date'], 'trim'],
            [['name', 'email', 'phone'], 'required'],

            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            // ['email', 'unique', 'targetClass' => '\backend\models\Staff', 'message' => Yii::t('app', 'email_exist'), 'filter' => "id <> '$this->id'"],

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
            'department' => Yii::t('app', 'department'),
            'start_date' => Yii::t('app', 'start_date'),
            'end_date' => Yii::t('app', 'end_date'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return Staff|null the saved model or null if saving fails
     */
    public function save()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = $this->getStaff();
        $user->name = $this->name;
        $user->avatar = $this->avatar;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->birthday = $this->birthday;
        $user->gender = $this->gender;
        $user->description = $this->description;
        $user->department = $this->department;
        $user->start_date = $this->start_date;
        $user->end_date = $this->end_date;
        return $user->save() ? $user : null;
    }

    public function getStaffGenders()
    {
        return Staff::getStaffGenders();
    }

    public function validateStaff($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $staff = $this->getStaff();
            if (!$staff) {
                $this->addError($attribute, Yii::t('app', 'invalid_staff'));
            }
        }
    }

    protected function getStaff()
    {
        if ($this->_staff === null) {
            $this->_staff = Staff::findOne($this->id);
        }

        return $this->_staff;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $staff = $this->getStaff();

        $this->name = $staff->name;
        $this->avatar = $staff->avatar;
        $this->email = $staff->email;
        $this->phone = $staff->phone;
        $this->address = $staff->address;
        $this->birthday = $staff->birthday;
        $this->gender = $staff->gender;
        $this->description = $staff->description;
        $this->department = $staff->department;
        $this->start_date = $staff->start_date;
        $this->end_date = $staff->end_date;
    }
}
