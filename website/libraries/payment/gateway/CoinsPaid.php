<?php

namespace website\libraries\payment\gateway;

use website\libraries\payment\BasePayment;
use website\libraries\payment\PaymentConstants;
use yii\base\ErrorException;
use yii\helpers\VarDumper;

class CoinsPaid extends BasePayment
{
    const PING_SUCCESS_STATUS = 'OK';

    const CURRENCY_USD = 'USD';
    const CURRENCY_CNY = 'CNY';

    const TRANSACTION_STATUS_CONFIRMED = 'confirmed';
    const TRANSACTION_STATUS_FAILED = 'failed';
    const TRANSACTION_STATUS_PROCESSING = 'processing';
    const TRANSACTION_STATUS_PENDING = 'pending';

    public function __construct()
    {
        parent::__construct(PaymentConstants::GATEWAY_COIN_PAID);
    }

    public function getSupportCurrency()
    {
        return [
            self::CURRENCY_USD,
            self::CURRENCY_CNY,
        ];
    }

    public function getTransactionStatus()
    {
        return [
            self::TRANSACTION_STATUS_CONFIRMED,
            self::TRANSACTION_STATUS_FAILED,
            self::TRANSACTION_STATUS_PROCESSING,
            self::TRANSACTION_STATUS_PENDING,
        ];
    }

    public function checkGateWayAlive()
    {
        try {
            $serviceUrl = $this->genEndPoint('ping');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $serviceUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);

            if ($res == self::PING_SUCCESS_STATUS) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            // log the error
            throw $e;
        }
    }

    public function generateGateWayUrl($order)
    {
        $data = [
            'timer' => true,
            'title' => $order['title'],
            'currency' => $order['currency'],
            'amount' => $order['amount'],
            'foreign_id' => $order['id'] . '',
            'url_success' => $order['url_success'],
            'url_failed' => $order['url_failed'],
            'email_user' => $order['user_email'],
        ];

        return $this->createInvoice($data);
    }

    private function createInvoice($order)
    {
        $signature = $this->generateSignature(json_encode($order));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getConfigOptions('terminalUrl'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-Processing-Key: ' . $this->getConfigOptions('apiPublicKey'),
            'X-Processing-Signature: ' . $signature
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $response = json_decode($server_output);

        if (isset($response->errors)) {
            print_r($response);
            die;
            throw new ErrorException('Data error'); // data error
        }

        return [
            'id' => $response->data->id,
            'hosted_url' => $response->data->url,
            'pricing' => $response->data->amount,
            'data' => $response->data
        ];
    }

    public function checkTransactionValid($data = [])
    {
        $signature = $this->generateSignature($data['payload']);
        return $signature == $data['signatureHeader'];
    }

    private function generateSignature($data)
    {
        return hash_hmac('sha512', $data, $this->getConfigOptions('apiSecretKey'));
    }
}