<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;
use website\models\AffiliateAccount;

class DeleteAffiliateAccountForm extends Model
{
    public $id;
    public $user_id;

    protected $_account;

    public function rules()
    {
        return [
            [['user_id', 'id'], 'required'],
            ['id', 'validateAccount'],
        ];
    }

    public function validateAccount($attribute, $params = [])
    {
        $account = $this->getAccount();
        if (!$account) {
            $this->addError($attribute, 'This account is not exist.');
            return;
        }
        $user = $this->getUser();
        if ($account->user_id != $user->id) {
            $this->addError($attribute, 'This account is not exist.');
            return;
        }
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function getAccount()
    {
        if (!$this->_account) {
            $this->_account = AffiliateAccount::findOne($this->id);
        }
        return $this->_account;
    }

    public function delete()
    {
        $account = $this->getAccount();
        return $account->delete();
    }
}

