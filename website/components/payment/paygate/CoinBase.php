<?php
namespace website\components\payment\paygate;

use Yii;
use yii\helpers\Url;
use website\libraries\payment\gateway\CoinBase as CoinBaseService;
use CoinbaseCommerce\Webhook;
use website\models\Order;
use website\forms\CreatePaymentRealityForm;

class CoinBase
{
    public $service;
    public $config;
    public function __construct($config)
    {
        $this->service = new CoinBaseService();
        $this->config = $config;
    }

    public function createCharge($order, $user = null)
    {
        $coinBase = $this->service;
        $data = ['order_id' => $order->id];
        $charge =  $coinBase->newCharge(
            $order->total_price,
            $order->game_title,
            $data,
            'USD'
        );
        $order->payment_data = json_encode($charge);
        $order->save();

        $form = new \website\forms\UpdateOrderForm(['id' => $order->id, 'payment_id' => $charge['id']]);
        if (!$form->update()) {
            $order->log(sprintf("[createCharge] process fail"));
            $order->log(json_encode($charge));
            $order->log(json_encode($form->getErrors()));
        } 
        $order->log(sprintf("[createCharge] process success"));
        return Url::to(['cart/thankyou', 'id' => $order->id], true);
    }

    public function processCharge()
    {
        Yii::info('start processCharge');
        $coinBase = $this->service;
        $secret = $coinBase->getWebHookSecret();
        $headerName = 'X-Cc-Webhook-Signature';
        $headers = getallheaders();
        $signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
        $payload = trim(file_get_contents('php://input'));
        try {
            Webhook::buildEvent($payload, $signraturHeader, $secret);
            $request = Yii::$app->request;
            $params = $request->bodyParams;
            $chargeData = [
                'data' => $params['event']['data'],
                'status' => $params['event']['type'],
            ];
            // $chargeData = $coinBase->buildChargeData($params);
            // save data to db update charge status
            $data = $chargeData['data'];
            $metadata = $data['metadata'];
            $paymentId = $data['id'];
            $note = $data['description'];
            $pricing = $data['pricing'];
            $localPrice = $pricing['local'];
            $orderId = $metadata['order_id'];
            $status = $chargeData['status'];
            $order = Order::findOne($orderId);
            $order->log(sprintf("processCharge chargeData"));
            $order->log(json_encode($chargeData));
            switch ($status) {
                case CoinBaseService::TRANSACTION_STATUS_CONFIRMED: {
                    // Create payment-reality
                    $realityData = [
                        'paygate' => 'CoinBase',
                        'payer' => $order->customer_name,
                        'payment_time' => date('Y-m-d H:i:s'),
                        'payment_id' => $paymentId,
                        'payment_note' => $note,
                        'total_amount' => $localPrice['amount'],
                        'currency' => $localPrice['currency'],
                        'note' => 'This payment is charged automatically by CoinBase',
                        'payment_type' => $this->config->getPaymentType()
                    ];
                    $realityForm = new CreatePaymentRealityForm($realityData);
                    if (!$realityForm->create()) { // process payment fail
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
                        ->setSubject('[Kinggems.us][RED ALERT] Coinbase callback fail')
                        ->setTextBody($message)
                        ->send();
                    }
                    return true;
                }
            }
            Yii::info('end processCharge');
        } catch (\Exception $exception) {
            // log error request
        }
    }
}