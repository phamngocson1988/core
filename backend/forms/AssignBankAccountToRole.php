<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\BankAccount;
use backend\models\BankAccountRole;

class AssignBankAccountToRole extends Model
{
    public $id;
    public $roles = [];

    protected $_bank_account;

    public function rules()
    {
        return [
            ['id', 'required', 'message' => 'Bạn phải nhập mã giao dịch'],
            ['id', 'validateAccount'],
            ['roles', 'safe'],
        ];
    }

    public function validateAccount($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $account = $this->getBankAccount();
        if (!$account) {
            $this->addError($attribute, 'Không tìm thấy tài khoản ngân hàng này.');
            return;
        }
    }

    public function assign()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $accountRoles = $this->getCurrentRoles();
            foreach ($accountRoles as $accountRole) {
                $accountRole->delete();
            }
            foreach ($this->roles as $name) {
                $accountRole = new BankAccountRole();
                $accountRole->bank_account_id = $this->id;
                $accountRole->role_id = $name;
                $accountRole->save();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

    public function getBankAccount()
    {
        if (!$this->_bank_account) {
            $this->_bank_account = BankAccount::findOne($this->id);
        }
        return $this->_bank_account;
    }

    public function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $names = ArrayHelper::map($roles, 'name', 'description');
        foreach (Yii::$app->user->fixRoles as $value) {
            unset($names[$value]);
        }
        return $names;
    }

    public function getCurrentRoles()
    {
        return BankAccountRole::find()
            ->where(['bank_account_id' => $this->id])->all();
    }

    public function loadCurrentRoles()
    {
        $currentRoles = $this->getCurrentRoles();
        $this->roles = ArrayHelper::getColumn($currentRoles, 'role_id');
    }
}
