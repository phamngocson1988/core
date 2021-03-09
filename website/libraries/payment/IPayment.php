<?php
namespace website\libraries\payment;

interface IPayment
{
    /**
     * @return array
     */
    function getGateWayConfig();

    /**
     * @return bool
     */
    function checkGateWayAlive();

    /**
     * @return array
     */
    function getSupportCurrency();

    /**
     * @return array
     */
    function getTransactionStatus();

    /**
     * @return mixed
     */
    function getPaymentServiceFee();

    /**
     * @param $data
     * @return mixed
     */
    function checkTransactionValid($data);
}