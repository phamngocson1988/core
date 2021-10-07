<?php

namespace website\libraries\payment\gateway;

use website\libraries\payment\BasePayment;
use website\libraries\payment\PaymentConstants;

class Binance extends BasePayment
{
    protected $mode = 'production'; // production || sandbox

    public function __construct()
    {
        parent::__construct(PaymentConstants::GATEWAY_BINANCE);
    }

    public function generateNonce($length = 32)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // config
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getServerTime()
    {
        $url = $this->getConfigOptions('timerUrl');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($output, true);

        if (isset($result['serverTime'])) {
            return $result['serverTime'];
        }
        return null;
    }

    public function signContent($payloadString)
    {
        $secretKey = $this->getConfigOptions('secretKey');
        $hash = hash_hmac("sha512", $payloadString, $secretKey);
        return strtoupper($hash);
    }
    
    public function getBinancePubLicKey()
    {
        // from config
        $url = $this->getConfigOptions('certificateUrl');
        $apiKey = $this->getConfigOptions('apiKey');
        $timestamp = $this->getServerTime();
        $nonce = $this->generateNonce();
        $entityBody = '';
        $payloadString = $timestamp . "\n" . $nonce . "\n" . $entityBody . "\n";
        $signContent = $this->signContent($payloadString);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'BinancePay-Timestamp: ' . $timestamp,
            'BinancePay-Nonce: ' . $nonce,
            'BinancePay-Certificate-SN: ' . $apiKey,
            'BinancePay-Signature: ' . $signContent
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($output, true);
    
        if (isset($result['status']) && $result['status'] == 'SUCCESS') {
            return $result['data'][0]['certPublic'];
        }
    
        return null;
    }

    public function createOrder($orderData)
    {
        // from config
        $url = $this->getConfigOptions('orderUrl');
        $apiKey = $this->getConfigOptions('apiKey');
        $timestamp = $this->getServerTime();
        $nonce = $this->generateNonce();
        $data = [
            "merchantId" => $this->getConfigOptions('merchantId'), // from config
            "merchantTradeNo" => $orderData['id'], // from order id
            "totalFee" => $orderData['amount'], // from order
            "currency" => "USDT",
            "tradeType" => "WEB",
            "productType" => "Game",
            "productName" => $orderData['title'], // from order
            "productDetail" => $orderData['description'],  // from order
        ];

        $body = json_encode($data);
        $payloadString = $timestamp . "\n" . $nonce . "\n" . $body . "\n";
        $signContent = $this->signContent($payloadString);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'BinancePay-Timestamp: ' . $timestamp,
            'BinancePay-Nonce: ' . $nonce,
            'BinancePay-Certificate-SN: ' . $apiKey,
            'BinancePay-Signature: ' . $signContent
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($server_output, true);
        // TODO: implement logic here
        return $response;
    }

    public function validateWebHook($requestHeaders, $requestBody)
    {
        $publicKey = $this->getBinancePubLicKey();
        $payload = $requestHeaders['Binancepay-Timestamp'] . "\n" . $requestHeaders['Binancepay-Nonce'] . "\n" . $requestBody . "\n";
        $decodedSignature = base64_decode($requestHeaders['Binancepay-Signature']);

        return openssl_verify($payload, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256) == 1;
    }

}