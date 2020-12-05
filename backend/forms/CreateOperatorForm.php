<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Operator;
use backend\models\OperatorMeta;
use backend\models\OperatorStaff;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;

class CreateOperatorForm extends Model
{
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

    public $admin_id;
    public $subadmin_ids;
    public $moderator_ids;
    public $language;

    public $_admin;
    public $_subadmins;
    public $_moderators;
	
    public function init()
    {
        $languages = array_keys(Yii::$app->params['languages']);
        if (!in_array($this->language, $languages)) {
            $this->language = reset($languages);
        }
    }
    
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],


            ['main_url', 'trim'],
            ['main_url', 'string', 'max' => 255],

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

            ['language', 'required'],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function validateAdmin($attribute, $params = [])
    {
        $user = $this->getAdmin();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'The user is not exist'));
        } elseif ($user->isOperatorStaff()) {
            $this->addError($attribute, Yii::t('app', sprintf("User %s already a staff of another operator", $user->email)));
        }   
    }

    public function validateSubAdmin($attribute, $params = [])
    {
        if ($this->hasError()) return;
        $users = $this->getSubAdmins();
        foreach ($users as $user) {
            if ($user->isOperatorStaff()) {
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
        if ($this->hasError()) return;
        $users = $this->getModerators();
        foreach ($users as $user) {
            if ($user->isOperatorStaff()) {
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

    public function create()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $operator = new Operator();
            $operator->main_url = $this->main_url;
            $operator->overview = $this->overview;
            $operator->backup_url = $this->backup_url;
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
            $operator->language = $this->language;
            $operator->save();

            $adminStaff = new OperatorStaff();
            $adminStaff->operator_id = $operator->id;
            $adminStaff->user_id = $this->admin_id;
            $adminStaff->role = OperatorStaff::ROLE_ADMIN;
            $adminStaff->save();

            foreach ((array)$this->subadmin_ids as $subId) {
                $subAdminStaff = new OperatorStaff();
                $subAdminStaff->operator_id = $operator->id;
                $subAdminStaff->user_id = $subId;
                $subAdminStaff->role = OperatorStaff::ROLE_SUBADMIN;
                $subAdminStaff->save();
            }

            foreach ((array)$this->moderator_ids as $modId) {
                $moderatorStaff = new OperatorStaff();
                $moderatorStaff->operator_id = $operator->id;
                $moderatorStaff->user_id = $modId;
                $moderatorStaff->role = OperatorStaff::ROLE_MODERATOR;
                $moderatorStaff->save();
            }

            $transaction->commit();
            return $operator;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('name', $e->getMessage());
            return false;
        }
    }

    public function fetchUsers()
    {
        $users = User::find()
        ->select(['id', 'email'])
        ->all();
        return ArrayHelper::map($users, 'id', 'email');
    }

    public function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
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
}
