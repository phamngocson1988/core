<?php
namespace website\components\payment\paygate;

use Yii;
use yii\helpers\Url;
use website\libraries\payment\gateway\CoinsPaid as CoinsPaidService;
use website\models\Order;
use website\models\PaymentTransaction;
use website\forms\CreatePaymentRealityForm;
use common\components\helpers\StringHelper;

class CoinsPaid
{
    public $service;
    public $config;
    public function __construct($config)
    {
        $this->service = new CoinsPaidService();
        $this->config = $config;
    }

    public function createCharge($order, $user = null)
    {
        if ($order instanceof Order) {
            return $this->createChargeFromOrder($order, $user);
        } elseif ($order instanceof PaymentTransaction) {
            return $this->createChargeFromDeposit($order, $user);
        }
    }

    protected function createChargeFromOrder($order, $user = null)
    {
        $coinPaid = $this->service;
        $orderUrl = Url::to(['order/index', '#' => $order->id], true);
        $orderData = [
            'title' => StringHelper::truncate(sprintf("#%s - %s", $order->id, $order->game_title), 45),
            'currency' => 'USDTT',// $order->currency,
            'amount' => $order->total_price, //$order->total_price_by_currency,
            'id' => $order->id,
            'url_success' => $orderUrl,
            'url_failed' => $orderUrl,
            'user_email' => $order->customer_email,
            'sender_currency' => 'USDTT',
            'timer' => true
        ];
        $charge =  $coinPaid->generateGateWayUrl($orderData);
        $form = new \website\forms\UpdateOrderForm([
            'id' => $order->id, 
            'payment_id' => $charge['id'],
            'payment_data' => json_encode($charge)
        ]);
        if (!$form->update()) {
            $order->log(sprintf("[createCharge] process fail"));
            $order->log(json_encode($charge));
            $order->log(json_encode($form->getErrors()));
        } 
        $order->log(sprintf("[CoinsPaid][createCharge] process success"));
        return Url::to(['order/index', '#' => $order->id], true);
    }

    protected function createChargeFromDeposit($order, $user = null) 
    {
        $coinPaid = $this->service;
        $orderUrl = Url::to(['wallet/index'], true);
        if (!$user) {
            $user = $order->user;
        }
        $orderData = [
            'title' => sprintf("#%s - %s", $order->getId(), 'Deposit'),
            'currency' => 'USDTT',
            'amount' => $order->total_price,
            'id' => $order->getId(),
            'url_success' => $orderUrl,
            'url_failed' => $orderUrl,
            'user_email' => $user->email,
            'sender_currency' => 'USDTT',
            'timer' => true
        ];
        $charge =  $coinPaid->generateGateWayUrl($orderData);
        $form = new \website\forms\UpdateTransactionForm([
            'id' => $order->id, 
            'payment_id' => $charge['id'],
            'payment_data' => json_encode($charge)
        ]);
        $form->update();
        return $orderUrl;
    }

    public function processCharge()
    {
        Yii::error('start processCharge');
        if (!$this->checkCallbackData()) {
            return false;
        }
        try {
            $request = Yii::$app->request;
            Yii::error('start processCharge request');
            Yii::error($request);
            $params = $request->bodyParams;
            Yii::error('start processCharge params');
            Yii::error($params);

            $orderId = $params['foreign_id'];
            if (strpos($orderId, PaymentTransaction::ID_PREFIX) === false) {
                $this->processOrder($params);
            } else {
                $this->processDeposit($params);
            }
            
            Yii::error('end processCharge');
        } catch (\Exception $exception) {
            // log error request
        }
    }

    protected function checkCallbackData() 
    {
        $coinPaid = $this->service;
        $headerName = 'X-Processing-Signature';
        $headers = getallheaders();
        Yii::error('start processCharge header');
        Yii::error($headers);
        $signatureHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
        $payload = trim(file_get_contents('php://input'));
        $check = [
            'payload' => $payload,
            'signatureHeader' => $signatureHeader,
        ];
        if (!$coinPaid->checkTransactionValid($check)) {
            return false;
        }
        return true;
    }

    protected function processOrder($params) 
    {
        $orderId = $params['foreign_id'];
        $order = Order::findOne($orderId);
        $status = $params['status'];
        $paymentId = $params['id'];
        // order status: pending, failed, processing
        if ($status == CoinsPaidService::TRANSACTION_STATUS_CONFIRMED) {
            // Create payment-reality
            $order->log("[CoinsPaid][Callback] Create reality payment data");
            $realityData = [
                'paygate' => 'CoinsPaid',
                'payer' => $order->customer_name,
                'payment_time' => date('Y-m-d H:i:s'),
                'payment_id' => $paymentId,
                'payment_note' => '',
                'total_amount' => $order->total_price_by_currency,
                'currency' => $order->currency,
                'note' => 'This payment is charged automatically by CoinsPaid',
                'payment_type' => $this->config->getPaymentType()
            ];
            $realityForm = new CreatePaymentRealityForm($realityData);
            if (!$realityForm->create()) { // process payment fail
                $order->log(sprintf("[CoinsPaid][Callback] Create reality payment data faillure"));
                // send mail notify to admin 
                $order->log("Coin base payment callback fail");
                $order->log(json_encode($realityForm->getErrors()));
                $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
                $adminTeams = User::find()->where(['id' => $adminTeamIds])->select(['email'])->asArray()->all();
                $adminEmails = array_column($adminTeams, 'email');
                $toEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
                $siteName = Yii::$app->name;
                $message = sprintf("System has received a payment for order %s but process fail. Please see order log for more detail", $orderId);
                Yii::$app->mailer->compose('alert_admin', ['message' => $message])
                ->setTo($adminEmails)
                ->setFrom([$toEmail => $siteName])
                ->setSubject('[Kinggems.us][RED ALERT] CoinsPaid callback fail')
                ->setTextBody($message)
                ->send();
            } else {
                $order->log(sprintf("[CoinsPaid][Callback] Create reality payment data successfully"));
            }
            // return true;
        }
    }

    protected function processDeposit($params) 
    {
        $transactionIdWithPrefix = $params['foreign_id'];
        $transactionId = str_replace(PaymentTransaction::ID_PREFIX, "", $transactionIdWithPrefix);
        $order = PaymentTransaction::findOne($transactionId);
        $status = $params['status'];
        $paymentId = $params['id'];
        // order status: pending, failed, processing
        if ($status == CoinsPaidService::TRANSACTION_STATUS_CONFIRMED) {
            // Create payment-reality
            $user = $order->user;
            $realityData = [
                'paygate' => 'CoinsPaid',
                'payer' => $user->getName(),
                'payment_time' => date('Y-m-d H:i:s'),
                'payment_id' => $paymentId,
                'payment_note' => '',
                'total_amount' => $order->total_price,
                'currency' => 'USD',
                'note' => 'This payment is charged automatically by CoinsPaid',
                'payment_type' => $this->config->getPaymentType()
            ];
            $realityForm = new CreatePaymentRealityForm($realityData);
            if (!$realityForm->create()) { // process payment fail
                $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
                $adminTeams = User::find()->where(['id' => $adminTeamIds])->select(['email'])->asArray()->all();
                $adminEmails = array_column($adminTeams, 'email');
                $toEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
                $siteName = Yii::$app->name;
                $message = sprintf("System has received a payment for order %s but process fail. Please see order log for more detail", $orderId);
                Yii::$app->mailer->compose('alert_admin', ['message' => $message])
                ->setTo($adminEmails)
                ->setFrom([$toEmail => $siteName])
                ->setSubject('[Kinggems.us][RED ALERT] CoinsPaid callback fail')
                ->setTextBody($message)
                ->send();
            }
        }
    }
}