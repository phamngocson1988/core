<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\CurrencySetting;
use yii\helpers\ArrayHelper;

class CreateCurrencyForm extends Model
{
    public $code;
    public $name;
    public $exchange_rate;
    public $symbol;
    public $format;
    public $status;

    public function rules()
    {
        return [
            [['code', 'name', 'exchange_rate', 'status', 'symbol', 'format'], 'trim'],
            [['code', 'name', 'exchange_rate', 'status'], 'required'],
            ['code', 'validateCode']
        ];
    }

    public function validateCode($attribute, $params = []) 
    {
        $currency = CurrencySetting::find()->where(['code' => $this->code])->exists();
        if ($currency) {
            return $this->addError($attribute, 'Mã tiền tệ này đã tồn tại');
        }
    }
    public function create()
    {
        if (!$this->validate()) return false;
        $currenty = new CurrencySetting();
        $currenty->code = $this->code;
        $currenty->name = $this->name;
        $currenty->symbol = $this->symbol;
        $currenty->format = $this->format;
        $currenty->exchange_rate = $this->exchange_rate;
        $currenty->status = $this->status;
        return $currenty->save();
    }

    public function fetchStatus()
    {
        return CurrencySetting::fetchStatus();
    }
}
