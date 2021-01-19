<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\CurrencySetting;
use yii\helpers\ArrayHelper;

class EditCurrencyForm extends Model
{
    public $code;
    public $name;
    public $symbol;
    public $format;
    public $exchange_rate;
    public $status;

    protected $_currency;

    public function rules()
    {
        return [
            [['code', 'name', 'exchange_rate', 'status', 'symbol', 'format'], 'trim'],
            [['code', 'name', 'exchange_rate', 'status'], 'required'],
            ['code', 'validateCode']
        ];
    }

    public function getCurrency()
    {
        if (!$this->_currency) {
            $this->_currency = CurrencySetting::findOne(['code' => $this->code]);
        }
        return $this->_currency;
    }

    public function validateCode($attribute, $params = []) 
    {
        $currency = $this->getCurrency();
        if (!$currency) {
            return $this->addError($attribute, sprintf('Mã tiền tệ %s không tồn tại', $this->code));
        }
    }
    public function edit()
    {
        if (!$this->validate()) return false;

        $currency = $this->getCurrency();
        $currency->name = $this->name;
        $currency->symbol = $this->symbol;
        $currency->format = $this->format;
        $currency->exchange_rate = $this->exchange_rate;
        $currency->status = $this->status;
        return $currency->save();
    }

    public function fetchStatus()
    {
        return CurrencySetting::fetchStatus();
    }

    public function loadData()
    {
        $currency = $this->getCurrency();
        $this->name = $currency->name;
        $this->exchange_rate = $currency->exchange_rate;
        $this->status = $currency->status;
        $this->format = $currency->format;
        $this->symbol = $currency->symbol;
    }
}
