<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\BankAccount;
use backend\models\BankAccountRole;

class FetchBankAccountByRoleForm extends Model
{
    public $roles = [];

    public function fetch()
    {
        $command = BankAccountRole::find();
        $command->where(["IN", "role_id", $this->roles]);
        $command->select(["bank_account_id"]);
        $models = $command->all();
        $ids = ArrayHelper::getColumn($models, 'bank_account_id');
        return BankAccount::find()->where(["IN", "id", $ids])->all();
    }
}
