<?php
namespace payment\forms;

use Yii;
use yii\base\Model;
use common\models\UserReseller;

class LoginForm extends Model
{
    public $code;
    
    private $_reseller;

    public function rules()
    {
        return [
            ['code', 'required'],
            ['code', 'validateCode']
        ];
    }

    public function validateCode($attribute, $params) 
    {
        $reseller = $this->getReseller();
        if (!$reseller) {
            return $this->addError($attribute, 'Reseller not found');
        }

        $user = $reseller->user;
        if (!$user) {
            return $this->addError($attribute, 'User not found');
        }

        if (!$user->isActive()) {
            return $this->addError($attribute, 'User not found');
        }
    }


    public function login()
    {
        if (!$this->validate()) return false;
        $reseller = $this->getReseller();
        $user = $reseller->user;
        return Yii::$app->user->login($user);
    }

    protected function getReseller()
    {
        if (!$this->_reseller) {
            $this->_reseller = UserReseller::findOne(['code' => $this->code]);
        }
        return $this->_reseller;
    }
}
