<?php

namespace website\libraries\payment\gateway;

use website\libraries\payment\BasePayment;
use website\libraries\payment\PaymentConstants;

class WebMoney extends BasePayment
{
    protected $mode = 'production'; // production || sandbox

    public function __construct()
    {
        parent::__construct(PaymentConstants::GATEWAY_WEBMONEY);
    }

    public function generateGateWayUrl()
    {
        return 'https://merchant.webmoney.ru/lmi/payment_utf.asp';
    }

    public function generateSignature($data)
    {
        $signData = [
            $this->getPayeePurse(),
            $data['amount'], // from order
            $data['id'], // from order
            $this->getConfigOptions('x20Secret'), // x20 secret key from config x20Secret
        ];
        $signString = implode(';', $signData) . ';';
        return hash('sha256', $signString);
    }

    public function getPayeePurse()
    {
        return $this->getConfigOptions('payeePurse');
    }

    public function checkSignRequest($request)
    {
        if (!isset($request['LMI_SYS_INVS_NO'])) {
            return false;
        }

        $signData = [
            $request['LMI_PAYEE_PURSE'] ?? '',
            $request['LMI_PAYMENT_AMOUNT'] ?? '',
            $request['LMI_PAYMENT_NO'] ?? '',
            $request['LMI_MODE'] ?? '',
            $request['LMI_SYS_INVS_NO'] ?? '',
            $request['LMI_SYS_TRANS_NO'] ?? '',
            $request['LMI_SYS_TRANS_DATE'] ?? '',
            $this->getConfigOptions('applicationSecret'), // application secret from config difference with x20 secret key
            $request['LMI_PAYER_PURSE'] ?? '',
            $request['LMI_PAYER_WM'] ?? '',
        ];

        $signString = implode(';', $signData);
        $hash = strtoupper(hash('sha256', $signString));

        return $hash === $request['LMI_HASH2'];
    }
}