<?php
namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\User;

class EditProfileForm extends Model
{
    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 255],
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
    }

    public function getId()
    {
        $user = $this->getUser();
        return $user->id;
    }
}
