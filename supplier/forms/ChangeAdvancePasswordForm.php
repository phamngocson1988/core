<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\behaviors\UserSupplierBehavior;

class ChangeAdvancePasswordForm extends Model
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
        if ($this->validate()) {
            $user = $this->getUser();
            $supplier = $this->getSupplier();
            $supplier->password = $this->new_password;
            return $supplier->save(false);
        }
        return false;
    }


    protected function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    protected function getSupplier()
    {
        $user = $this->getUser();
        $user->attachBehavior('supplier', new UserSupplierBehavior);
        return $user->supplier;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $supplier = $this->getSupplier();
            if (!$supplier->password && !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, Yii::t('app', 'incorrect_password'));
            } elseif ($supplier->password && $supplier->password != $this->old_password) {
                $this->addError($attribute, Yii::t('app', 'incorrect_password'));
            }
        }
    }
}
