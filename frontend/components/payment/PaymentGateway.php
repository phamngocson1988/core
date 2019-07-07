<?php
namespace frontend\components\payment;

use Yii;
use yii\base\Model;
use frontend\components\payment\cart\PaymentCart;
use yii\helpers\ArrayHelper;
use frontend\components\payment\events\BeforeRequestEvent;
use yii\helpers\Url;

class PaymentGateway extends Model
{
    const EVENT_BEFORE_REQUEST = 'EVENT_BEFORE_REQUEST';
    const EVENT_BEFORE_CONFIRM = 'EVENT_BEFORE_CONFIRM';
    const EVENT_AFTER_CONFIRM = 'EVENT_AFTER_CONFIRM';
    const EVENT_BEFORE_CANCEL = 'EVENT_BEFORE_CANCEL';
    const EVENT_AFTER_CANCEL = 'EVENT_AFTER_CANCEL';
    const EVENT_CONFIRM_SUCCESS = 'EVENT_CONFIRM_SUCCESS';
    const EVENT_CONFIRM_ERROR = 'EVENT_CONFIRM_ERROR';

    public $confirm_url = 'topup/verify';
    public $success_url = 'topup/success';
    public $cancel_url = 'topup/cancel';
    public $error_url = 'topup/error';

    public $identifier;

    /** @var <any> unique ID for every payment request */
    protected $reference_id;

    /** @var array include parameter names of confirm request */
    protected $confirmParams = [];

    /** @var PaymentCart */
    protected $cart;

    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    public function request()
    {
        $this->trigger(self::EVENT_BEFORE_REQUEST);
        return $this->sendRequest();
    }

    public function confirm()
    {
        $this->initResponseParams();
        $this->trigger(self::EVENT_BEFORE_CONFIRM);
        $response = $this->getConfirmParams();
        Yii::info($response, 'skirll_response');
        Yii::info($this->getReferenceId(), 'skirll_response');
        $result = $this->verify($response);
        $this->trigger(self::EVENT_AFTER_CONFIRM);
        if ($result) {
            Yii::info($this->identifier . $this->getReferenceId() . " confirm success");
            $this->trigger(self::EVENT_CONFIRM_SUCCESS);
            return $this->doSuccess();
        } else {
            Yii::info($this->identifier . $this->getReferenceId() . " confirm failure");
            $this->trigger(self::EVENT_CONFIRM_ERROR);
            return $this->doError();
        }
        return $result;
    }

    public function cancel()
    {
        $this->initResponseParams();
        $this->trigger(self::EVENT_BEFORE_CANCEL);
        $result = $this->cancelPayment();
        $this->trigger(self::EVENT_AFTER_CANCEL);
        return $result;
    }

    public function getReferenceId()
    {
        if (!$this->reference_id) $this->reference_id = md5(date('YmdHis') . Yii::$app->user->id);
        return $this->reference_id;
    }

    protected function getQueryParam($name, $defaultValue = null)
    {
        $request = Yii::$app->getRequest();
        $params = $request->getQueryParams();
        return isset($params[$name]) && is_scalar($params[$name]) ? $params[$name] : $defaultValue;
    }

    protected function getQueryParams()
    {
        $request = Yii::$app->getRequest();
        $get = $request->getQueryParams();
        $post = $request->getBodyParams();
        $params = array_merge($get, $post);
        $params = array_filter($params, function($var) { return is_scalar($var); });
        return $params;
    }

    protected function initResponseParams()
    {
        $this->reference_id = $this->getQueryParam('ref');
    }

    protected function getConfirmParams()
    {
        $params = (array)$this->confirmParams;

        // Return all query param if not declare confirm params
        if (empty($params)) return $this->getQueryParams();

        $response = [];
        foreach ($params as $name) {
            $response[$name] = $this->getQueryParam($name);
        }
        return $response;
    }

    protected function redirect($link)
    {
        return Yii::$app->getResponse()->redirect($link, 302);
    }

    protected function getConfirmUrl($params = [])
    {
        $params['identifier'] = $this->identifier;
        $params['ref'] = $this->getReferenceId();
        return Url::to(array_merge([$this->confirm_url], $params), true);
    }

    protected function getSuccessUrl($params = [])
    {
        return Url::to(array_merge([$this->success_url], $params), true);
    }

    protected function getCancelUrl($params = [])
    {
        $params['identifier'] = $this->identifier;
        $params['ref'] = $this->getReferenceId();
        return Url::to(array_merge([$this->cancel_url], $params), true);
    }

    protected function getErrorUrl($params = [])
    {
        return $this->error_url;
        return Url::to(array_merge([$this->error_url], $params), true);
    }

    protected function doSuccess()
    {

    }

    protected function doError()
    {

    }
}