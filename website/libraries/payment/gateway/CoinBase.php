<?php

namespace website\libraries\payment\gateway;

use website\libraries\payment\BasePayment;
use website\libraries\payment\PaymentConstants;
use yii\base\ErrorException;
use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;

class CoinBase extends BasePayment
{
    const CURRENCY_CNY = 'CNY';
    const CURRENCY_USD = 'USD';

    const TRANSACTION_STATUS_CREATED = 'charge:created';
    const TRANSACTION_STATUS_CONFIRMED = 'charge:confirmed';
    const TRANSACTION_STATUS_FAILED = 'charge:failed';
    const TRANSACTION_STATUS_DELAYED= 'charge:delayed';
    const TRANSACTION_STATUS_PENDING = 'charge:pending';

    protected $mode = 'production'; // production
    
    public function __construct()
    {
        parent::__construct(PaymentConstants::GATEWAY_COIN_BASE);
        ApiClient::init($this->getConfigOptions('apiSecret'));
    }

    public function getSupportCurrency()
    {
        return [
            self::CURRENCY_CNY,
            self::CURRENCY_USD,
        ];
    }

    public function getTransactionStatus()
    {
        return [
            self::TRANSACTION_STATUS_CREATED,
            self::TRANSACTION_STATUS_CONFIRMED,
            self::TRANSACTION_STATUS_FAILED,
            self::TRANSACTION_STATUS_DELAYED,
            self::TRANSACTION_STATUS_PENDING,
        ];
    }

    public function checkGateWayAlive()
    {
        return true;
    }

    public function newCharge($amount, $title, $data = [], $currency = self::CURRENCY_USD)
    {
        $charge =  $this->createCharge($amount, $title, $data, $currency);
        $chargeInfo = $this->getChargeInfo($charge->id);
        $info = json_decode($chargeInfo, 1);
        $chargeData = $info['data'];

        $chargeUrl = $chargeData['hosted_url'];
        $expiresAt = date("d-m-Y H:i:s", strtotime($chargeData['expires_at']));
        $chargeId = $chargeData['id'];
        $chargeCode = $chargeData['code'];
        $pricing = $chargeData['pricing'];
        $addresses = $chargeData['addresses'];

        return [
            'id' => $chargeId,
            'code' => $chargeCode,
            'hosted_url' => $chargeUrl,
            'expires_at' => $expiresAt,
            'pricing' => $pricing,
            'addresses' => $addresses,
        ];
    }

    /**
     * @param $amount
     * @param $title
     * @param array $data array of data order id or something that will return on callback
     * @param string $currency
     * @return Charge
     * @throws ErrorException
     */
    public function createCharge($amount, $title, $data = [], $currency = self::CURRENCY_USD)
    {
        $data = [
            "name" => $title,
            "description" => 'Payment for ' . $title,
            "metadata" => $data,
            "local_price" => [
                "amount" => $amount,
                "currency" => $currency
            ],
            "pricing_type" => "fixed_price"
        ];
        $chargeObj = new Charge($data);
        try {
            $chargeObj->save();
            return $chargeObj;
        } catch (\Exception $e) {
            throw new ErrorException("Error when trying to charge.");
        }
    }

    public function getChargeInfo($chargeId)
    {
        try {
            $apiUrl = $this->getConfigOptions('apiUrl') . 'charges/' . $chargeId;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'X-CC-Api-Key:' . $this->getConfigOptions('apiSecret'),
                'X-CC-Version: 2018-03-22'
            ));
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);

            return $res;
        } catch (\Exception $e) {
            // log the error
            throw new ErrorException("Error when trying to charge.");
        }
    }

    public function getWebHookSecret()
    {
        return $this->getConfigOptions('webHookSecret');
    }

    public function buildChargeData($params)
    {
        $chargeData =  $params['event']['data'];

        return [
            'data' => $chargeData,
            'status' => $params['event']['type']
        ];
    }
}