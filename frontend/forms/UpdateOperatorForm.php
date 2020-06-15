<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\Operator;
use common\models\Country;

class UpdateOperatorForm extends Model
{
    public $id;
    public $name;
    public $main_url;
    public $backup_url;
    public $withdrawal_limit;
    public $withdrawal_currency;
    public $rebate;
    public $owner;
    public $established;
    public $livechat_support;
    public $support_email;
    public $support_phone;
    public $logo;
    protected $_operator;
    
    public function rules()
    {
        return [
            ['name', 'trim'],
            [['name', 'main_url', 'owner', 'support_email', 'support_phone'], 'string', 'max' => 255],
            ['backup_url', 'string', 'max' => 1024],
            ['withdrawal_currency', 'string', 'max' => 16],
            [['established', 'rebate', 'withdrawal_limit'], 'number'],
        ];
    }

    public function save()
    {
        $operator = $this->getOperator();
        $operator->main_url = $this->main_url;
        $operator->backup_url = $this->backup_url;
        $operator->withdrawal_limit = $this->withdrawal_limit;
        $operator->withdrawal_currency = $this->withdrawal_currency;
        $operator->rebate = $this->rebate;
        $operator->owner = $this->owner;
        $operator->established = $this->established;
        $operator->livechat_support = $this->livechat_support;
        $operator->support_email = $this->support_email;
        $operator->support_phone = $this->support_phone;
        return $operator->save();
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->id);
        }
        return $this->_operator;
    }

    public function loadData()
    {
        $operator = $this->getOperator();
        $this->name = $operator->name;
        $this->main_url = $operator->main_url;
        $this->backup_url = $operator->backup_url;

        $this->withdrawal_limit = $operator->withdrawal_limit;
        $this->withdrawal_currency = $operator->withdrawal_currency;
        $this->rebate = $operator->rebate;
        $this->owner = $operator->owner;
        $this->established = $operator->established;
        $this->livechat_support = $operator->livechat_support;
        $this->support_email = $operator->support_email;
        $this->support_phone = $operator->support_phone;
    }

    public function getImageUrl($size = null, $default = 'https://www.ira-sme.net/wp-content/themes/consultix/images/no-image-found-360x260.png')
    {
        $operator = $this->getOperator();
        return $operator->getImageUrl($size, $default);
    }

    public function fetchEstablishedYear()
    {
        $years = range(1990, date("Y"));
        return array_combine($years, $years);
    }
}
