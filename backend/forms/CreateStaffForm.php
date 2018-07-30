<?php
namespace backend\forms;

use yii\base\Model;
use backend\models\Staff;

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
    public $department;
    public $start_date;
    public $end_date;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'avatar', 'email', 'phone', 'address', 'birthday', 'gender', 'description', 'department', 'start_date', 'end_date'], 'trim'],
            [['name', 'email', 'phone'], 'required'],

            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\Staff', 'message' => 'This email address has already been taken.'],

            ['gender', 'in', 'range' => array_keys(Staff::getStaffGenders())],

            
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
        $user->department = $this->department;
        $user->start_date = $this->start_date;
        $user->end_date = $this->end_date;
        return $user->save() ? $user : null;
    }

    public function getStaffGenders()
    {
        return Staff::getStaffGenders();
    }
}
