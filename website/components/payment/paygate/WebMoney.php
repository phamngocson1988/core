<?php
namespace website\components\payment\paygate;

use Yii;
use yii\helpers\Url;
use website\models\Order;
use common\models\PaymentTransaction;
use website\forms\CreatePaymentRealityForm;
use common\components\helpers\StringHelper;
use website\libraries\payment\gateway\WebMoney as WebMoneyService;

class WebMoney
{
    public $service;
    public $config;

    public function __construct($config)
    {
        $this->service = new WebMoneyService();
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
        $info = [
            'paygate_url' => $this->service->generateGateWayUrl(),
            'LMI_PAYMENT_AMOUNT' => $order->total_price_by_currency,
            'PAYMENT_TYPE' => 'order',
            'LMI_PAYMENT_DESC' => sprintf("#%s - %s", $order->id, $order->game_title),
            'LMI_PAYMENT_NO' => $order->id,
            'LMI_PAYEE_PURSE' => $this->service->getPayeePurse(),
            'LMI_PAYMENTFORM_SIGN' => $this->service->generateSignature(['id' => $order->id, 'amount' => $order->total_price_by_currency]),
        ];
        if (!$this->service->isProduction()) {
            $info['LMI_MODE'] = 1;
            $info['LMI_SIM_MODE'] = 0;
        }
        $form = new \website\forms\UpdateOrderForm([
            'id' => $order->id, 
            'payment_id' => $order->id,
            'payment_data' => json_encode($info)
        ]);
        if (!$form->update()) {
            $order->log(sprintf("[createCharge] process fail"));
            $order->log(json_encode($info));
            $order->log(json_encode($form->getErrors()));
        } 
        $order->log(sprintf("[Webmoney][createCharge] process success"));
        return Url::to(['order/index', '#' => $order->id], true);
    }

    protected function createChargeFromDeposit($order, $user = null) 
    {
        $orderUrl = Url::to(['wallet/index'], true);
        $info = [
            'paygate_url' => $this->service->generateGateWayUrl(),
            'LMI_PAYMENT_AMOUNT' => $order->total_price,
            'PAYMENT_TYPE' => 'deposit',
            'LMI_PAYMENT_DESC' => sprintf("#%s - %s", $order->getId(), 'Deposit'),
            'LMI_PAYMENT_NO' => $order->id,
            'LMI_PAYEE_PURSE' => $this->service->getPayeePurse(),
            'LMI_PAYMENTFORM_SIGN' => $this->service->generateSignature(['id' => $order->id, 'amount' => $order->total_price]),
        ];
        if (!$this->service->isProduction()) {
            $info['LMI_MODE'] = 1;
            $info['LMI_SIM_MODE'] = 0;
        }
        $form = new \website\forms\UpdateTransactionForm([
            'id' => $order->id, 
            'payment_id' => $order->getId(),
            'payment_data' => json_encode($info)
        ]);
        $form->update();
        return $orderUrl;
    }

    public function processCharge()
    {
        Yii::info('[Webmoney] processCharge');
        try {
            $request = Yii::$app->request;
            Yii::info('[Webmoney] processCharge request');
            Yii::info($request);
            $params = $request->post();
            Yii::info('[Webmoney] processCharge params');
            Yii::info($params);
            if (!$this->checkCallbackData($params)) {
                Yii::info('[Webmoney] processCharge failure');
                Yii::info($params);
                return false;
            }
            $orderId = $params['LMI_PAYMENT_NO'];
            $type = $params['PAYMENT_TYPE']; // order || deposit
            Yii::info(sprintf('[Webmoney] processCharge success %s - $s', $orderId, $type));
            if ($type === 'order') {
                $this->processOrder($params);
            } else {
                $this->processDeposit($params);
            }
            
            Yii::info('[Webmoney] end processCharge');
        } catch (\Exception $exception) {
            Yii::error('[Webmoney] processCharge catch error');
            Yii::error($exception);
            throw $exception;
        }
    }

    protected function checkCallbackData($request) 
    {
        return $this->service->checkSignRequest($request);
    }

    protected function processOrder($params) 
    {
        $orderId = $params['LMI_PAYMENT_NO'];
        $order = Order::findOne($orderId);
        $paymentId = $params['LMI_SYS_TRANS_NO'];
        // Create payment-reality
        $order->log("[Webmoney][Callback] Create reality payment data");
        $realityData = [
            'paygate' => $this->config->getIdentifier(),
            'payer' => $order->customer_name,
            'payment_time' => date('Y-m-d H:i:s'),
            'payment_id' => $orderId,
            'payment_note' => '',
            'total_amount' => $order->total_price_by_currency,
            'currency' => $order->currency,
            'note' => 'This payment is charged automatically by Webmoney - transaction no ' . $paymentId,
            'payment_type' => $this->config->getPaymentType()
        ];
        $realityForm = new CreatePaymentRealityForm($realityData);
        if (!$realityForm->create()) { // process payment fail
            $order->log(sprintf("[Webmoney][Callback] Create reality payment data faillure"));
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
            ->setSubject('[Kinggems.us][RED ALERT] Webmoney callback fail')
            ->setTextBody($message)
            ->send();
        } else {
            $order->log(sprintf("[Webmoney][Callback] Create reality payment data successfully"));
        }
    }

    protected function processDeposit($params) 
    {
        $transactionId = $params['LMI_PAYMENT_NO'];
        $order = PaymentTransaction::findOne($transactionId);
        $paymentId = $params['LMI_SYS_TRANS_NO'];
        // Create payment-reality
        $user = $order->user;
        $realityData = [
            'paygate' => $this->config->getIdentifier(),
            'payer' => $user->getName(),
            'payment_time' => date('Y-m-d H:i:s'),
            'payment_id' => $order->getId(),
            'payment_note' => '',
            'total_amount' => $order->total_price,
            'currency' => 'USD',
            'note' => 'This payment is charged automatically by Webmoney - transaction no ' . $paymentId,
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
            ->setSubject('[Kinggems.us][RED ALERT] Webmoney callback fail')
            ->setTextBody($message)
            ->send();
        }
    }
}