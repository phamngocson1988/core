<?php
namespace common\forms;

use Yii;
use common\forms\ActionForm;
use common\models\User;
use common\models\UserReseller;

class GenerateResellerCodeForm extends ActionForm
{
    public $user_id;
    protected $_user;
    protected $_code;

    public function rules()
    { 
        return [
            ['user_id', 'required'],
            ['user_id', 'validateUser'],
        ];
    }

    public function validateUser($attribute, $params) 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->addError($attribute, 'Reseller không tồn tại');
        }
        if (!$user->isReseller()) {
            return $this->addError($attribute, 'User không phải reseller');
        }
    }

    public function generate()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();
        $reseller = $user->reseller;
        if (!$reseller->code) {
            $reseller->code = $this->getCode();
            $reseller->save();
        }
        return $reseller->code;
    }

    public function getCode() 
    {
        if (!$this->_code) {
            $this->_code = Yii::$app->security->generateRandomString(20);
        }
        return $this->_code;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }
}
