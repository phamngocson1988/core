<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Paygate;
use frontend\models\PaymentTransaction;
use frontend\models\Promotion;
use frontend\models\Payment;
use common\components\helpers\FormatConverter;
// Notification
use frontend\components\notifications\DepositNotification;
use frontend\behaviors\DepositNotificationBehavior;
use frontend\components\payment\PaymentGatewayFactory;

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
            ['voucher', 'validateVoucher'],
        ];
    }

    public function getPaygate()
    {
        if (!$this->_paygate) {
            $this->_paygate = PaymentGatewayFactory::getClient($this->paygate);
        }
        return $this->_paygate;
    }

    public function setPaygate($paygate) 
    {
        $this->_paygate = $paygate;
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
        if (!$user->is_verify_phone && !$user->is_verify_email) {
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

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

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

            // payment info
            $exchangeRate = (float)$settings->get('ApplicationSettingForm', sprintf('exchange_rate_%s', strtolower($paygate->getCurrency())), 1);
            $paymentInfo = new Payment();
            $paymentInfo->user_id = $user->id;
            $paymentInfo->payment_method = $this->paygate;
            $paymentInfo->payment_type = $paygate->getPaymentType();
            $paymentInfo->payment_data = $paygate->content;
            $paymentInfo->amount = $data['totalPayment'] * $exchangeRate;
            $paymentInfo->currency = $paygate->getCurrency();
            $paymentInfo->amount_usd = $data['totalPayment'];
            $paymentInfo->exchange_rate = $exchangeRate;
            $paymentInfo->object_ref = 'wallet';
            $paymentInfo->object_key = $trn->id;
            $paymentInfo->status = Payment::STATUS_PENDING;
            $paymentInfo->save();

            $trn->attachBehavior('notification', DepositNotificationBehavior::className());
            $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
            $trn->pushNotification(DepositNotification::NOTIFY_SALER_NEW_ORDER, $salerTeamIds);
            
            $transaction->commit();

            return $trn->id;

        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('cart', $e->getMessage());
            return false;
        }
    }
}