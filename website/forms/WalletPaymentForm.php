<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Paygate;
use website\models\PaymentTransaction;
use common\components\helpers\FormatConverter;
// Notification
use website\components\notifications\DepositNotification;
use website\behaviors\DepositNotificationBehavior;

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

        // $fee = $paygate->transfer_fee;
        // if ($fee) {
        //     $type = $paygate->transfer_fee_type;
        //     $transferFee = $type == 'fix' ? $fee : number_format($fee * $subTotalPayment / 100, 1);
        //     $totalPayment += $transferFee;
        // } else {
        //     $transferFee = 0;
        // }
        $transferFee = $paygate->getFee($subTotalPayment);
        $totalPayment += $transferFee;
        return [
            'subTotalKingcoin' => round($subTotalKingcoin, 1, PHP_ROUND_HALF_UP),
            'bonusKingcoin' => round($bonusKingcoin, 1, PHP_ROUND_HALF_DOWN),
            'totalKingcoin' => round($totalKingcoin, 1, PHP_ROUND_HALF_UP),
            'subTotalPayment' => round($subTotalPayment, 1, PHP_ROUND_HALF_UP),
            'voucherApply' => round($voucherApply, 1, PHP_ROUND_HALF_DOWN),
            'transferFee' => round($transferFee, 1, PHP_ROUND_HALF_UP),
            'totalPayment' => round($totalPayment, 1, PHP_ROUND_HALF_UP),
        ];
    }

    public function purchase()
    {
        $data = $this->calculate();
        $user = Yii::$app->user->getIdentity();
        $request = Yii::$app->request;
        $settings = Yii::$app->settings;
        $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);

        // Save transaction
        $trn = new PaymentTransaction();
        $trn->user_id = $user->id;
        $trn->user_ip = $request->userIP;
        $trn->payment_method = $this->paygate;
        $trn->payment_type = 'offline';
        $trn->rate_usd = $rate;
        // Price
        $trn->price = $data['subTotalPayment'];
        $trn->discount_price = 0;
        $trn->total_fee = $data['transferFee'];
        $trn->total_price = $data['totalPayment'];
        // Coin
        $trn->coin = $data['subTotalKingcoin'];
        $trn->promotion_coin = $data['bonusKingcoin'];
        $trn->total_coin = $data['totalKingcoin'];
        $trn->description = $this->paygate;
        $trn->created_by = $user->id;
        $trn->status = PaymentTransaction::STATUS_PENDING;
        $trn->payment_at = date('Y-m-d H:i:s');
        $trn->generateAuthKey();
        $trn->save();

        $trn->attachBehavior('notification', DepositNotificationBehavior::className());
        $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
        $trn->pushNotification(DepositNotification::NOTIFY_SALER_NEW_ORDER, $salerTeamIds);

        return $trn->id;
    }
}