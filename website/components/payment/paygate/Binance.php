<?php
namespace website\components\payment\paygate;

use Yii;
use yii\helpers\Url;
use website\models\Order;
use common\models\PaymentTransaction;
use website\forms\CreatePaymentRealityForm;
use common\components\helpers\StringHelper;
use website\libraries\payment\gateway\Binance as BinanceService;
use common\models\PaymentCommitmentOrder;

class Binance
{
    public $service;
    public $config;

    public function __construct($config)
    {
        $this->service = new BinanceService();
        $this->config = $config;
    }

    public function createCharge($order, $user = null)
    {
        if (is_array($order)) {
            if ($order['bulk']) {
                return $this->createChargeFromBulk($order, $user);
            }
        } else {
            if ($order instanceof Order) {
                return $this->createChargeFromOrder($order, $user);
            } elseif ($order instanceof PaymentTransaction) {
                return $this->createChargeFromDeposit($order, $user);
            }
        }
        
    }

    /**
     * Convert payment information from bulk
     * Then send to paygate to create order on paygate platform
     * 
     * @param Array  $order  information of orders
     * @example ['total_price' => 10, 'description' => 'Some description', 'bulk' => 1020384839, 'title' => 'Title of order', 'orderIds' => [1,2,3]]
     * @param User  $user information of user who place the order
     */
    protected function createChargeFromBulk($order, $user = null) 
    {
        $info = [
            'amount' => $order['total_price'],
            'description' => $order['description'],
            'id' => 'B' . $order['id'],
            'title' => $order['title']
        ];
        $response = $this->service->createOrder($info);
        if ($response['status'] === 'SUCCESS') {
            /**
             * array (size=5)
             *   'prepayId' => string '119048806996172800' (length=18)
             *   'tradeType' => string 'WEB' (length=3)
             *   'expireTime' => int 1633276820989
             *   'qrcodeLink' => string 'https://public.bnbstatic.com/static/payment/20211003/40174d1b-e69c-4474-9b97-6bdeabd75f31.jpg' (length=93)
             *   'qrContent' => string 'https://app.binance.com/qr/dplk3e07da82190a4949ab1c8c9d0c81031a' (length=63)
             */ 
            $responseData = $response['data'];
            foreach ($order['orderIds'] as $orderId) {
                $paymentId = sprintf("%s_%s", $responseData['prepayId'], $orderId);
                $form = new \website\forms\UpdateOrderForm([
                    'id' => $orderId, 
                    'payment_id' => $paymentId,
                    'payment_data' => json_encode($responseData)
                ]);
                if (!$form->update()) {
                    $orderObject = Order::findOne($orderId);
                    $orderObject->log(sprintf("[Binance][createCharge] process fail responseData"));
                    $orderObject->log(json_encode($responseData));
                    $orderObject->log(json_encode($form->getErrors()));
                } 
                // For the bulk
                $commitment = PaymentCommitmentOrder::findOne(['object_key' => $orderId]);
                if ($commitment) {
                    $commitment->payment_id = $paymentId;
                    $commitment->save();
                }
            }
            
        } else {
            foreach ($order['orderIds'] as $orderId) {
                $orderObject = Order::findOne($orderId);
                $orderObject->log(sprintf("[Binance][createCharge] process fail response"));
                $orderObject->log(json_encode($response));
            }
        }
        return Url::to(['order/bulk'], true);
    }

    protected function createChargeFromOrder($order, $user = null)
    {
        $info = [
            'amount' => $order->total_price,
            'description' => sprintf("#%s - %s", $order->id, $order->game_title),
            'id' => $order->id,
            'title' => $this->cleanString($order->game_title)
        ];
        $response = $this->service->createOrder($info);
        if ($response['status'] === 'SUCCESS') {
            /**
             * array (size=5)
             *   'prepayId' => string '119048806996172800' (length=18)
             *   'tradeType' => string 'WEB' (length=3)
             *   'expireTime' => int 1633276820989
             *   'qrcodeLink' => string 'https://public.bnbstatic.com/static/payment/20211003/40174d1b-e69c-4474-9b97-6bdeabd75f31.jpg' (length=93)
             *   'qrContent' => string 'https://app.binance.com/qr/dplk3e07da82190a4949ab1c8c9d0c81031a' (length=63)
             */ 
            $responseData = $response['data'];
            $form = new \website\forms\UpdateOrderForm([
                'id' => $order->id, 
                'payment_id' => $responseData['prepayId'],
                'payment_data' => json_encode($responseData)
            ]);
            if (!$form->update()) {
                $order->log(sprintf("[Binance][createCharge] process fail responseData"));
                $order->log(json_encode($responseData));
                $order->log(json_encode($form->getErrors()));
            } 
        } else {
            $order->log(sprintf("[Binance][createCharge] process fail response"));
            $order->log(json_encode($response));
        }
        return Url::to(['order/index', '#' => $order->id], true);
    }

    protected function createChargeFromDeposit($order, $user = null) 
    {
        $orderUrl = Url::to(['wallet/index'], true);
        $info = [
            'amount' => $order->total_price,
            'description' => sprintf("#%s - %s", $order->getId(), 'Deposit'),
            'id' => $order->getId(),
            'title' => sprintf("#%s - %s", $order->getId(), 'Deposit'),
        ];
        $response = $this->service->createOrder($info);
        if ($response['status'] === 'SUCCESS') {
            /**
             * array (size=5)
             *   'prepayId' => string '119048806996172800' (length=18)
             *   'tradeType' => string 'WEB' (length=3)
             *   'expireTime' => int 1633276820989
             *   'qrcodeLink' => string 'https://public.bnbstatic.com/static/payment/20211003/40174d1b-e69c-4474-9b97-6bdeabd75f31.jpg' (length=93)
             *   'qrContent' => string 'https://app.binance.com/qr/dplk3e07da82190a4949ab1c8c9d0c81031a' (length=63)
             */ 
            $responseData = $response['data'];
            $form = new \website\forms\UpdateTransactionForm([
                'id' => $order->id, 
                'payment_id' => $responseData['prepayId'],
                'payment_data' => json_encode($responseData)
            ]);
            $form->update();
        }
        return $orderUrl;
    }

    public function processCharge()
    {
        Yii::info('[Binance] processCharge');
        if (!$this->checkCallbackData()) {
            return false;
        }
        try {
            $request = Yii::$app->request;
            Yii::info('[Binance] processCharge request');
            Yii::info($request);
            $params = $request->bodyParams;
            $params['data'] = json_decode($params['data'], true);
            Yii::info('[Binance] processCharge params');
            Yii::info($params);

            $orderId = $params['data']['merchantTradeNo'];
            if (strpos($orderId, PaymentTransaction::ID_PREFIX) !== false) {
                $this->processDeposit($params);
            } else if (strpos($orderId, 'B') !== false) {
                $this->processBulk($params);
            } else {
                $this->processOrder($params);
            }
            
            Yii::info('end processCharge');
        } catch (\Exception $exception) {
            Yii::error('[Binance] processCharge error');
            Yii::error($exception);
        }
    }

    protected function checkCallbackData() 
    {
        $request = Yii::$app->request;
        $headers = getallheaders();
        $responseData = $request->bodyParams;
        $params = json_encode($responseData);

        Yii::info('start processCharge header - body');
        Yii::info($headers);
        Yii::info($responseData);
        if (!$this->service->validateWebHook($headers, $params)) {
            return false;
        }
        // "bizType": "PAY",
        // "bizStatus": "PAY_SUCCESS" 
        if ($responseData['bizType'] != 'PAY' || $responseData['bizStatus'] != 'PAY_SUCCESS') {
            return false;
        }
 
        return true;
    }

    protected function processOrder($params) 
    {
        $orderId = $params['data']['merchantTradeNo'];
        $order = Order::findOne($orderId);
        $paymentId = $params['bizId'];
        // Create payment-reality
        $order->log("[Binance][Callback] Create reality payment data");
        $realityData = [
            'paygate' => $this->config->getIdentifier(),
            'payer' => $order->customer_name,
            'payment_time' => date('Y-m-d H:i:s'),
            'payment_id' => $paymentId,
            'payment_note' => '',
            'total_amount' => $order->total_price,
            'currency' => 'USD',
            'note' => 'This payment is charged automatically by Binance ' . $paymentId,
            'payment_type' => $this->config->getPaymentType()
        ];
        $realityForm = new CreatePaymentRealityForm($realityData);
        if (!$realityForm->create()) { // process payment fail
            $order->log(sprintf("[Binance][Callback] Create reality payment data faillure"));
            // send mail notify to admin 
            $order->log("Binance payment callback fail");
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
            ->setSubject('[Kinggems.us][RED ALERT] Binance callback fail')
            ->setTextBody($message)
            ->send();
        } else {
            $order->log(sprintf("[Binance][Callback] Create reality payment data successfully"));
        }
        // return true;
    }

    protected function processDeposit($params) 
    {
        $transactionIdWithPrefix = $params['data']['merchantTradeNo'];
        $transactionId = str_replace(PaymentTransaction::ID_PREFIX, "", $transactionIdWithPrefix);
        $order = PaymentTransaction::findOne($transactionId);
        $paymentId = $params['bizId'];
        // Create payment-reality
        $user = $order->user;
        $realityData = [
            'paygate' => $this->config->getIdentifier(),
            'payer' => $user->getName(),
            'payment_time' => date('Y-m-d H:i:s'),
            'payment_id' => $paymentId,
            'payment_note' => '',
            'total_amount' => $order->total_price,
            'currency' => 'USD',
            'note' => 'This payment is charged automatically by Binance ' . $paymentId,
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
            ->setSubject('[Kinggems.us][RED ALERT] Binance callback fail')
            ->setTextBody($message)
            ->send();
        }
    }

    protected function processBulk($params) 
    {
        $idWithPrefix = $params['data']['merchantTradeNo'];
        $id = str_replace("B", "", $idWithPrefix);
        $commitment = PaymentCommitment::findOne($id);
        $user = User::findOne($commitment->user_id);
        $paymentId = $params['bizId'];
        $childCommitments = PaymentCommitment::find()->where(['parent' => $id])->all();

        // Create payment-reality
        foreach ($childCommitments as $childCommitment) {
            $singleOrderParams = [
            'data' => ['merchantTradeNo' => $childCommitment->object_key],
            'bizId' => sprintf("%s_%s", $paymentId, $childCommitment->object_key)
            ];
            $this->processOrder($singleOrderParams);
        }
        
        return true;
    }

    protected function cleanString($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     
        $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
        $string = str_replace('-', ' ', $string); // Replaces all hyphens with spaces.
        return $string;
     }
}
