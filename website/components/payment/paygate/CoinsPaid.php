<?php
namespace website\components\payment\paygate;

use Yii;
use yii\helpers\Url;
use website\libraries\payment\gateway\CoinsPaid as CoinsPaidService;
use website\models\Order;
use website\forms\CreatePaymentRealityForm;

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
        $coinPaid = $this->service;
        $orderUrl = Url::to(['order/index', '#' => $order->id], true);
        $orderData = [
            'title' => sprintf("#%s - %s", $order->id, $order->game_title),
            'currency' => $order->currency,
            'amount' => $order->total_price_by_currency,
            'id' => $order->id,
            'url_success' => $orderUrl,
            'url_failed' => $orderUrl,
            'user_email' => $order->customer_email,
        ];
        $charge =  $coinPaid->generateGateWayUrl($orderData);
        $order->payment_data = json_encode($charge);
        $order->save();
        $form = new \website\forms\UpdateOrderForm(['id' => $order->id, 'payment_id' => $charge['id']]);
        if (!$form->update()) {
            $order->log(sprintf("[createCharge] process fail"));
            $order->log(json_encode($charge));
            $order->log(json_encode($form->getErrors()));
        } 
        $order->log(sprintf("[CoinsPaid][createCharge] process success"));
        return Url::to(['cart/thankyou', 'id' => $order->id], true);
    }

    public function processCharge()
    {
        Yii::info('start processCharge');
        $coinPaid = $this->service;
        $headerName = 'X-Processing-Signature';
        $headers = getallheaders();
        $signatureHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
        $payload = trim(file_get_contents('php://input'));
        $check = [
            'payload' => $payload,
            'signatureHeader' => $signatureHeader,
        ];
        // if (!$coinPaid->checkTransactionValid($check)) {
        //     return;
        // }
        try {
            $request = Yii::$app->request;
            $params = $request->bodyParams;
            $orderId = $params['foreign_id'];
            $order = Order::findOne($orderId);
            // if (!$orderId) return;
            $status = $params['status'];
            $paymentId = $params['id'];
            $order->log(sprintf("[CoinsPaid][Callback] %s", $status));
            $order->log(json_encode($params));
            // order status: pending, failed, processing
            if ($status == 'confirmed') {
                // Create payment-reality
                $order->log(sprintf("[CoinsPaid][Callback] Create reality payment data"));
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
            Yii::info('end processCharge');
        } catch (\Exception $exception) {
            // log error request
        }
    }
}