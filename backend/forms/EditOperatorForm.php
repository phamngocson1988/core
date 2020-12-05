<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Operator;
use yii\helpers\ArrayHelper;
use backend\models\OperatorMeta;
use backend\models\OperatorStaff;
use common\models\Country;

class EditOperatorForm extends Model
{
    public $id;
    public $name;
    public $overview;
    public $main_url;
    public $backup_url;
    public $withdrawal_limit;
    public $withdrawal_currency;
    public $withdrawal_time;
    public $withdrawal_method;
    public $rebate;
    public $owner;
    public $established;
    public $livechat_support;
    public $support_email;
    public $support_phone;
    public $logo;
    public $support_language;
    public $support_currency;
    public $license;
    public $product;
    public $deposit_method;
    public $status;

    public $admin_id;
    public $subadmin_ids;
    public $moderator_ids;

    public $_admin;
    public $_subadmins;
    public $_moderators;

    protected $_operator;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOperator'],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['main_url', 'trim'],
            ['main_url', 'string', 'max' => 255],

            ['status', 'required'],
            ['status', 'in', 'range' => array_keys($this->fetchStatus())],

            [['owner', 'support_email', 'support_phone'], 'string', 'max' => 255],
            ['withdrawal_currency', 'string', 'max' => 16],
            [['established', 'rebate', 'withdrawal_limit'], 'number'],
            [['support_language', 'support_currency', 'license', 'withdrawal_time', 'withdrawal_method', 'product', 'deposit_method', 'overview', 'backup_url'], 'safe'],

            ['admin_id', 'required'],
            ['admin_id', 'validateAdmin'],

            ['subadmin_ids', 'safe'],
            ['subadmin_ids', 'validateSubAdmin'],

            ['moderator_ids', 'safe'],
            ['moderator_ids', 'validateModerator'],
        ];
    }

    public function validateAdmin($attribute, $params = [])
    {
        $user = $this->getAdmin();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'The user is not exist'));
        } elseif ($user->isNotOperatorStaffOf($this->id) && $user->isOperatorStaff()) {
            $this->addError($attribute, Yii::t('app', sprintf("User %s already a staff of another operator", $user->email)));
        }   
    }

    public function validateSubAdmin($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $users = $this->getSubAdmins();
        if (count($users) != count($this->subadmin_ids)) {
            $this->addError($attribute, sprintf("Some users are not exist"));
            return;
        }
        foreach ($users as $user) {
            if ($user->isNotOperatorStaffOf($this->id) && $user->isOperatorStaff()) {
                $this->addError($attribute, sprintf("User %s already a staff of another operator", $user->email));
                return;
            }  
        }

        $subs = (array)$this->subadmin_ids;
        $mods = (array)$this->moderator_ids;
        if (count(array_intersect($subs, $mods)) || in_array($this->admin_id, $subs)) {
            $this->addError($attribute, 'One user should be assigned to one role');
            return;
        }
    }

    public function validateModerator($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $users = $this->getModerators();
        if (count($users) != count($this->moderator_ids)) {
            $this->addError($attribute, sprintf("Some users are not exist"));
            return;
        }
        foreach ($users as $user) {
            if ($user->isNotOperatorStaffOf($this->id) && $user->isOperatorStaff()) {
                $this->addError($attribute, sprintf("User %s already a staff of another operator", $user->email));
                return;
            }  
        }

        $subs = (array)$this->subadmin_ids;
        $mods = (array)$this->moderator_ids;
        if (count(array_intersect($subs, $mods)) || in_array($this->admin_id, $mods)) {
            $this->addError($attribute, 'One user should be assigned to one role');
            return;
        }
    }

    public function validateOperator($attribute, $pararms = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            $this->addError($attribute, Yii::t('app', 'operator_is_not_exist'));
        }
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->id);
        }
        return $this->_operator;
    }

    public function update()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $operator = $this->getOperator();
            $operator->name = $this->name;
            $operator->main_url = $this->main_url;
            $operator->overview = $this->overview;
            $operator->backup_url = implode(",", $this->backup_url);
            $operator->withdrawal_limit = $this->withdrawal_limit;
            $operator->withdrawal_currency = $this->withdrawal_currency;
            $operator->rebate = $this->rebate;
            $operator->owner = $this->owner;
            $operator->established = $this->established;
            $operator->livechat_support = $this->livechat_support;
            $operator->support_email = $this->support_email;
            $operator->support_phone = $this->support_phone;

            // new
            $operator->support_language = implode(",", (array)$this->support_language);
            $operator->support_currency = implode(",", (array)$this->support_currency);
            $operator->withdrawal_method = implode(",", (array)$this->withdrawal_method);
            $operator->product = implode(",", (array)$this->product);
            $operator->deposit_method = implode(",", (array)$this->deposit_method);
            $operator->withdrawal_time = $this->withdrawal_time;
            $operator->license = $this->license;
            $operator->status = $this->status;
            $operator->save();

            $staffs = OperatorStaff::find()->where(['operator_id' => $operator->id])->all();
            foreach ((array)$staffs as $staff) {
                $staff->delete();
            }
            $newAdminStaff = new OperatorStaff();
            $newAdminStaff->operator_id = $operator->id;
            $newAdminStaff->user_id = $this->admin_id;
            $newAdminStaff->role = OperatorStaff::ROLE_ADMIN;
            $newAdminStaff->save();

            if ($this->subadmin_ids) {
                foreach ((array)$this->subadmin_ids as $subId) {
                    $subAdminStaff = new OperatorStaff();
                    $subAdminStaff->operator_id = $operator->id;
                    $subAdminStaff->user_id = $subId;
                    $subAdminStaff->role = OperatorStaff::ROLE_SUBADMIN;
                    $subAdminStaff->save();
                }
            }

            if ($this->moderator_ids) {
                foreach ((array)$this->moderator_ids as $modId) {
                    $moderatorStaff = new OperatorStaff();
                    $moderatorStaff->operator_id = $operator->id;
                    $moderatorStaff->user_id = $modId;
                    $moderatorStaff->role = OperatorStaff::ROLE_MODERATOR;
                    $moderatorStaff->save();
                }
            }

            $transaction->commit();
            return $operator;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('name', $e->getMessage());
            return false;
        }
        
    }

    public function loadData()
    {
        $operator = $this->getOperator();
        $this->name = $operator->name;
        $this->main_url = $operator->main_url;
        $this->overview = $operator->overview;
        $this->backup_url = explode(',', $operator->backup_url);

        $this->withdrawal_limit = $operator->withdrawal_limit;
        $this->withdrawal_currency = $operator->withdrawal_currency;
        $this->rebate = $operator->rebate;
        $this->owner = $operator->owner;
        $this->established = $operator->established;
        $this->livechat_support = (boolean)$operator->livechat_support;
        $this->support_email = $operator->support_email;
        $this->support_phone = $operator->support_phone;

        // new
        $this->support_currency = explode(",", $operator->support_currency);
        $this->support_language = explode(",", $operator->support_language);
        $this->withdrawal_time = $operator->withdrawal_time;
        $this->withdrawal_method = explode(",", $operator->withdrawal_method);
        $this->product = explode(",", $operator->product);
        $this->deposit_method = explode(",", $operator->deposit_method);
        $this->license = $operator->license;
        $this->status = $operator->status;

        // staff
        $staffs = OperatorStaff::find()->where(['operator_id' => $this->id])->all();
        $admins = array_filter($staffs, function($staff) {
            return $staff->role === OperatorStaff::ROLE_ADMIN;
        });
        $subAdmins = array_filter($staffs, function($staff) {
            return $staff->role === OperatorStaff::ROLE_SUBADMIN;
        });
        $moderators = array_filter($staffs, function($staff) {
            return $staff->role === OperatorStaff::ROLE_MODERATOR;
        });
        $admin = reset($admins);
        $this->admin_id = $admin ? $admin->user_id : null;
        $this->subadmin_ids = ArrayHelper::getColumn($subAdmins, 'user_id');
        $this->moderator_ids = ArrayHelper::getColumn($moderators, 'user_id');
    }

    public function fetchEstablishedYear()
    {
        $years = range(1990, date("Y"));
        return array_combine($years, $years);
    }

    public function fetchLanguage()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'language', []);
    }

    public function fetchCurrency()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'currency', []);
    }

    public function fetchLiveChat()
    {
        return [
            '1' => 'Yes',
            '0' => 'No',
        ];
    }

    public function fetchWithdrawTime()
    {
        return [
            "E-wallets: 0m – 24h" => "E-wallets: 0m – 24h",
            "Card Payments: 0m – 24h" => "Card Payments: 0m – 24h",
            "Bank Transfers: 3-5d" => "Bank Transfers: 3-5d",
            "Cheques: 3-5d" => "Cheques: 3-5d",
        ];
    }

    public function fetchWithdrawMethod()
    {
        $withdrawal = OperatorMeta::find()->where(['key' => OperatorMeta::KEY_WITHDRAWVAL_METHOD])->one();
        $values = $withdrawal ? explode(",", $withdrawal->value) : [];
        if (count($values)) {
            return array_combine($values, $values);
        }
        return [];
    }

    public function fetchProduct()
    {
        $product = OperatorMeta::find()->where(['key' => OperatorMeta::KEY_PRODUCT])->one();
        $values = $product ? explode(",", $product->value) : [];
        if (count($values)) {
            return array_combine($values, $values);
        }
        return [];
    }

    public function fetchDepositMethod()
    {
        $deposit = OperatorMeta::find()->where(['key' => OperatorMeta::KEY_DEPOSIT_METHOD])->one();
        $values = $deposit ? explode(",", $deposit->value) : [];
        if (count($values)) {
            return array_combine($values, $values);
        }
        return [];
    }

    public function fetchLicense()
    {
        $license = OperatorMeta::find()->where(['key' => OperatorMeta::KEY_LICENSE])->one();
        $values = $license ? explode(",", $license->value) : [];
        if (count($values)) {
            return array_combine($values, $values);
        }
        return [];
    }

    public function fetchBackupUrls()
    {
        $urls = explode(',', $this->backup_url);
        return array_combine($urls, $urls);
    }

    public function fetchStatus()
    {
        return Operator::getStatusList();
    }

    public function getAdmin()
    {
        if (!$this->_admin && $this->admin_id) {
            $this->_admin = User::findOne($this->admin_id);
        }
        return $this->_admin;
    }

    public function getSubAdmins()
    {
        if (!$this->_subadmins) {
            $this->_subadmins = User::findAll($this->subadmin_ids);
        }
        return $this->_subadmins;
    }

    public function getModerators()
    {
        if (!$this->_moderators) {
            $this->_moderators = User::findAll($this->moderator_ids);
        }
        return $this->_moderators;
    }

    public function fetchUsers()
    {
        $users = User::find()
        ->select(['id', 'email'])
        ->all();
        return ArrayHelper::map($users, 'id', 'email');
    }
}
