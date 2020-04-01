<?php
namespace backend\forms;

use Yii;
use backend\models\Bank;
use yii\helpers\ArrayHelper;
use common\components\helpers\CommonHelper;

class EditBankForm extends Bank
{
    public function rules()
    {
        return [
            ['code', 'trim'],
            ['code', 'required', 'message' => 'Bạn hãy nhập mã ngân hàng'],
            ['code', 'uniqueCode'],

            ['name', 'trim'],
            ['name', 'required', 'message' => 'Bạn hãy nhập tên ngân hàng'],

            ['country', 'trim'],
            ['country', 'required', 'message' => 'Bạn hãy chọn một quốc gia cho ngân hàng'],

            ['currency', 'trim'],
            ['currency', 'required', 'message' => 'Bạn hãy chọn loại tiền tệ của ngân hàng'],

            ['transfer_cost', 'default', 'value' => 0],
            ['transfer_cost', 'number'],

            ['transfer_cost_type', 'trim'],
            ['transfer_cost_type', 'default', 'value' => Bank::TRANSER_COST_TYPE_FIX],
            ['transfer_cost_type', 'in', 'range' => [Bank::TRANSER_COST_TYPE_FIX, Bank::TRANSER_COST_TYPE_PERCENT]],
        ];
    }

    public function uniqueCode($attribute, $params = []) 
    {
        $bank = Bank::findOne($this->id);
        if ($bank->code === $this->code) return;
        if (Bank::find()->where(['code' => $this->code])->count() > 0) {
            $this->addError($attribute, 'Mã ngân hàng bị trùng');
        }
    }

    public function attributeLabels()
    {
        return [
            'code' => 'Mã ngân hàng',
            'name' => 'Tên ngân hàng',
            'country' => 'Quốc gia',
            'currency' => 'Loại tiền tệ',
            'transfer_cost' => 'Phí chuyển khoản',
            'transfer_cost_type' => 'Cách tính phí chuyển khoản',
        ];
    }

    public function fetchCountry()
    {
        return CommonHelper::fetchCountry();
    }

    public function fetchCurrency()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'currency', []);
    }

    public function fetchTransferCostType()
    {
        return [
            Bank::TRANSER_COST_TYPE_FIX => 'Phí cố định',
            Bank::TRANSER_COST_TYPE_PERCENT => 'Phí tính theo phần trăm',
        ];
    }
}
