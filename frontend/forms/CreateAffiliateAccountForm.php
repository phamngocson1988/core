<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\AffiliateAccount;

class CreateAffiliateAccountForm extends Model
{
    public $user_id;
    public $payment_method;
    public $account_number;
    public $account_name;
    public $region;

    public function rules()
    {
        return [
            [['user_id', 'payment_method', 'account_name', 'account_number', 'region'], 'required'],
            ['user_id', 'validateAffiliate'],
            ['user_id', 'validateLimit'],
        ];
    }

    public function validateLimit($attribute, $params = [])
    {
        $count = AffiliateAccount::find()->where(['user_id' => $this->user_id])->count();
        if ($count >= 4) {
            $this->addError($attribute, 'Cannot add more bank account.');
            return;
        }
    }

    public function validateAffiliate($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user->isAffiliate()) {
            $this->addError($attribute, 'You are not an affiliate.');
            return;
        }
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function create()
    {
        $account = new AffiliateAccount();
        $account->user_id = $this->user_id;
        $account->payment_method = $this->payment_method;
        $account->account_number = $this->account_number;
        $account->account_name = $this->account_name;
        $account->region = $this->region;
        return $account->save();
    }
}

