<?php
namespace website\libraries\payment;

use Yii;
use yii\base\ErrorException;

abstract class BasePayment implements IPayment
{
    protected $gateWayId;
    /**
     * @var array|void
     */
    protected $gateWayConfig;

    protected $mode = 'sandbox'; // sandbox production

    public function __construct($gateWayId)
    {
        $this->gateWayId = $gateWayId;
        $this->gateWayConfig = $this->getGateWayConfig();
    }

    /**
     * get payment gateway config from file
     * change get config base on framework
     * @return array|void
     */
    public function getGateWayConfig()
    {
        $configList = Yii::$app->params['payment'][$this->gateWayId];
        return $configList[$this->mode];
    }

    /**
     * Check whether config is production
     *
     * @return boolean
     */
    public function isProduction()
    {
        return $this->mode === 'production';
    }

    /**
     * implement for each payment gateway if exists
     * if not support always return true
     * @return bool
     */
    public function checkGateWayAlive()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getSupportCurrency()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getTransactionStatus()
    {
        return [];
    }

    /**
     * get payment fee via gateway
     * @return mixed|void
     */
    public function getPaymentServiceFee()
    {
        return 0;
    }

    /**
     * check valid transaction
     * @param $data
     * @return mixed|void
     */
    public function checkTransactionValid($data)
    {
        return true;
    }

    /**
     * override if want to create api endpoint
     * @param $endPath
     * @return string
     */
    protected function genEndPoint($endPath)
    {
        return $this->getConfigOptions('apiEndPoint') . $endPath;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    protected function getConfigOptions($key) {
        if ($key && isset($this->gateWayConfig[$key])) {
            return $this->gateWayConfig[$key];
        }

        return null;
    }
}