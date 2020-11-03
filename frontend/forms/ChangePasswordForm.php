<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $re_password;

    private $_user;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_password', 'new_password', 're_password'], 'required'],
            ['old_password', 'validatePassword'],
            ['re_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    public function change()
    {
        $user = $this->getUser();
        $user->setPassword($this->new_password);
        return $user->save(false);
    }


    protected function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect password'));
            }
        }
    }
}

