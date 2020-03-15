<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;

class EditProfileForm extends Model
{
    public $name;
    public $phone;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 255],

            ['phone', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Tên nhân viên',
            'email' => 'Hộp thư điện tử',
            'phone' => 'Số điện thoại',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = $this->getUser();
        $user->name = $this->name;
        $user->phone = $this->phone;
        return $user->save() ? $user : null;
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function loadData()
    {
        $user = $this->getUser();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
    }

    public function getId()
    {
        $user = $this->getUser();
        return $user->id;
    }
}
