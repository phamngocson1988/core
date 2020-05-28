<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Paygate;
use common\components\helpers\FormatConverter;

class WalletPaymentForm extends Model
{
    public $quantity;
    public $voucher;
    public $paygate;

    protected $_paygate;

    public function rules()
    {
        return [
            [['quantity', 'paygate'], 'required'],
            [['voucher'], 'trim'],
            ['quantity', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            ['paygate', 'validatePaygate'],
            ['voucher', 'validateVoucher']
        ];
    }

    public function getPaygate()
    {
        if (!$this->_paygate) {
            $this->_paygate = Paygate::find()
            ->where(['identifier' => $this->paygate])
            ->andWhere(['status' => Paygate::STATUS_ACTIVE])
            ->one();
        }
        return $this->_paygate;
    }

    public function validatePaygate($attribute, $params = [])
    {
        $paygate = $this->getPaygate();
        if (!$paygate) {
            $this->addError($attribute, sprintf('Payment Gateway %s is not available', $this->paygate));
        }
    }

    public function validateVoucher($attribute, $params = [])
    {
        if (!$this->voucher) return;
    }

    public function calculate()
    {
        $paygate = $this->getPaygate();
        $subTotalPayment = $this->quantity;
        if ($paygate->currency == 'CNY') {
            $subTotalPayment = FormatConverter::convertCurrencyToCny($this->quantity);
        }
        $totalPayment = $subTotalPayment;
        
        $subTotalKingcoin = $this->quantity;
        $totalKingcoin = $subTotalKingcoin;
        
        $voucherApply = false;
        $bonusKingcoin = 0;

        $fee = $paygate->transfer_fee;
        if ($fee) {
            $type = $paygate->transfer_fee_type;
            $transferFee = $type == 'fix' ? $fee : number_format($fee * $subTotalPayment / 100, 1);
        } else {
            $transferFee = 0;
        }
        return [
            'subTotalKingcoin' => $subTotalKingcoin,
            'totalKingcoin' => $totalKingcoin,
            'subTotalPayment' => $subTotalPayment,
            'totalPayment' => $totalPayment,
            'voucherApply' => $voucherApply,
            'bonusKingcoin' => $bonusKingcoin,
            'transferFee' => $transferFee
        ];
    }
}