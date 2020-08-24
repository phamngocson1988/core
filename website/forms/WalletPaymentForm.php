<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Paygate;
use website\models\PaymentTransaction;
use website\models\Promotion;
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
    protected $_promotion;

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

    public function getPromotion()
    {
        if (!$this->_promotion) {
            $this->_promotion = Promotion::findOne(['code' => $this->voucher]);
        }
        return $this->_promotion;
    }

    public function validatePaygate($attribute, $params = [])
    {
        $paygate = $this->getPaygate();
        if (!$paygate) {
            $this->addError($attribute, sprintf('Payment Gateway %s is not available', $this->paygate));
        }
    }

    public function validateVoucher($attribute, $params)
    {
        if (!$this->voucher) return;
        $promotion = $this->getPromotion();
        if (!$promotion) {
            $this->addError($attribute, 'This voucher code is not valid');
            return;
        }
        $user = Yii::$app->user->getIdentity();
        if (!$user->phone) {
            $this->addError($attribute, 'Your account is not eligible for this promotion.');
            $this->_promotion = null;
            return;
        }
        if ($promotion->promotion_scenario != Promotion::SCENARIO_BUY_COIN) {
            $this->addError($attribute, 'This voucher code is not valid');
            $this->_promotion = null;
            return;
        }
        if (!$promotion->canApplyForUser(Yii::$app->user->id)) {
            $this->addError($attribute, 'This voucher code is not valid for this user');
            $this->_promotion = null;
            return;
        }
    }

    public function calculate()
    {
        $paygate = $this->getPaygate();
        $subTotalPayment = $this->quantity;
        $totalPayment = $subTotalPayment;
        $promotion = $this->getPromotion();
        $subTotalKingcoin = $this->quantity;
        $totalKingcoin = $subTotalKingcoin;

        $bonusKingcoin = 0;
        if ($promotion) {
            $bonusKingcoin = $promotion->apply($subTotalKingcoin);
            $totalKingcoin += $bonusKingcoin;
        }
        $voucherApply = $promotion ? true : false;
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
        $paygate = $this->getPaygate();

        // Save transaction
        $trn = new PaymentTransaction();
        $trn->user_id = $user->id;
        $trn->user_ip = $request->userIP;
        $trn->payment_method = $this->paygate;
        $trn->payment_type = 'offline';
        $trn->payment_data = $paygate->content;
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