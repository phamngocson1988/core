<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Operator;
use yii\helpers\ArrayHelper;
use frontend\models\OperatorMeta;
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

            [['owner', 'support_email', 'support_phone'], 'string', 'max' => 255],
            ['withdrawal_currency', 'string', 'max' => 16],
            [['established', 'rebate', 'withdrawal_limit'], 'number'],
            [['support_language', 'support_currency', 'license', 'withdrawal_time', 'withdrawal_method', 'product', 'deposit_method', 'overview', 'backup_url'], 'safe'],
        ];
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
        return $operator->save();
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

    public function fetchBackupUrls()
    {
        $urls = explode(',', $this->backup_url);
        return array_combine($urls, $urls);
    }
}
