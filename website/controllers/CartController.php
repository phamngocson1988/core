<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use website\components\cart\CartItem;
use website\models\Paygate;
use website\models\Order;
use common\models\Currency;
use common\models\CurrencySetting;
use common\components\helpers\StringHelper;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'blockip' => [
                'class' => \website\components\filters\BlockIpAccessControl::className(),
                'except' => [
                    'payment-coin-base-callback',
                    'payment-coin-paid-callback',
                    'payment-webmoney-callback',
                    'payment-binance-callback'
                ]
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['calculate', 'calculates', 'add', 'payment-coin-base-callback', 'payment-coin-paid-callback', 'payment-webmoney-callback', 'payment-binance-callback'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'checkout', 'checkouts', 'bulk', 'calculate-bulk', 'thankyou', 'calculate-cart', 'payment-success'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'payment-coin-base-callback' => ['post'],
                    'payment-coin-paid-callback' => ['post'],
                    'payment-webmoney-callback' => ['post'],
                    'payment-binance-callback' => ['post']
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        $paymentActions = [
            'payment-coin-base-callback',
            'payment-coin-paid-callback',
            'payment-webmoney-callback',
            'payment-binance-callback'
        ];
        if (in_array($action->id, $paymentActions)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionCalculate($id) 
    {
        Yii::info('actionCalculate');
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $model = CartItem::findOne($id);
        $model->setScenario(CartItem::SCENARIO_CALCULATE_CART);
        if ($model->load($request->post()) && $model->validate()) {
            $totalPrice = $model->getTotalPrice();
            $subTotalPrice = $model->getSubTotalPrice();
            $promotionDiscount = $model->getPromotionDiscount();
            $unit = $model->getTotalUnit();
            $unitName = $model->getUnitName();
            $origin = $model->getTotalOriginalPrice();
            return $this->asJson(['status' => true, 'data' => [
                'sub-price' => StringHelper::numberFormat($subTotalPrice, 2),
                'promotion-discount' => StringHelper::numberFormat($promotionDiscount, 2),
                'amount' => StringHelper::numberFormat($totalPrice, 2),
                'origin' => StringHelper::numberFormat($origin, 2),
                'unit' => sprintf("%s %s", StringHelper::numberFormat($unit), strtoupper($unitName)),
            ]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return $this->asJson(['status' => false, 'errors' => $message]);
        }
    }


    public function actionAdd($id) 
    {
        $request = Yii::$app->request;

        $model = CartItem::findOne($id);
        $model->setScenario(CartItem::SCENARIO_ADD_CART);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $cart = Yii::$app->cart;
            $cart->clear();
            $cart->add($model);
            return $this->redirect(['cart/index']);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            Yii::$app->session->setFlash('error', $message);
            return $this->redirect($request->getReferrer());
        }
    }

    public function actionIndex()
    {
        $cart = Yii::$app->cart;
        $model = $cart->getItem();
        $model->setScenario(CartItem::SCENARIO_UPDATE_CART);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $cart = Yii::$app->cart;
            $cart->clear();
            $cart->add($model);
            return $this->redirect(['cart/checkout']);
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionCalculateCart() 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'errors' => 'You need to login']);

        $request = Yii::$app->request;
        $cart = Yii::$app->cart;
        $user = Yii::$app->user->getIdentity();
        
        $form = new \website\forms\OrderPaymentForm([
            'cart' => $cart,
            'paygate' => $request->post('paygate'),
        ]);

        if ($form->validate()) {
            $calculate = $form->calculate();
            $subTotalPayment = $calculate['subTotalPayment'];
            $promotionDiscount = $calculate['promotionDiscount'];
            $transferFee = $calculate['transferFee'];
            $totalPayment = $calculate['totalPayment'];
            $paygate = $form->getPaygateConfig();
            $currencyModel = CurrencySetting::findOne(['code' => $paygate->getCurrency()]);
            $usdCurrency = CurrencySetting::findOne(['code' => 'USD']);
            $otherCurrencyTotal = $usdCurrency->exchangeTo($totalPayment, $currencyModel);
            $otherCurrency = $currencyModel->showByFormat(StringHelper::numberFormat($otherCurrencyTotal, 2));
            $data = [
                'subTotalPayment' => $usdCurrency->showByFormat(StringHelper::numberFormat($subTotalPayment, 2)),
                'promotionDiscount' => $usdCurrency->showByFormat(StringHelper::numberFormat($promotionDiscount, 2)),
                'transferFee' => $usdCurrency->showByFormat(StringHelper::numberFormat($transferFee, 2)),
                'totalPayment' => $usdCurrency->showByFormat($totalPayment),
                'otherCurrency' => $otherCurrency,
                'isOtherCurrency' => $paygate->getCurrency() != 'USD'
            ];
            return $this->asJson(['status' => true, 'data' => $data]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $form->getErrorSummary(true)]);
        }
    }

    public function actionCheckout()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->cart;
        $user = Yii::$app->user->getIdentity();
        
        $checkoutForm = new \website\forms\OrderPaymentForm(['cart' => $cart]);
        if ($checkoutForm->load($request->post()) && $checkoutForm->validate() && $id = $checkoutForm->purchase()) {
            $paygate = $checkoutForm->getPaygate();
            $order = Order::findOne($id);
            $this->redirect($paygate->createCharge($order, $user));
        } else {
            Yii::$app->session->setFlash('error', $checkoutForm->getErrorSummary(true));
        }
        $model = $cart->getItem();
        $balance = $user->getWalletAmount();
        $canPlaceOrder = $balance >= $cart->getTotalPrice();
        $paygates = Paygate::find()->where(['status' => Paygate::STATUS_ACTIVE])->all();
        $isOtherCurrency = $model->currency != 'USD';
        $otherCurrency = '';
        if ($isOtherCurrency) {
            $usdCurrency = CurrencySetting::findOne(['code' => 'USD']);
            $currencyModel = CurrencySetting::findOne(['code' => $model->currency]);
            $otherCurrencyTotal = $usdCurrency->exchangeTo($model->getTotalPrice(), $currencyModel);
            $otherCurrency = $currencyModel->showByFormat(StringHelper::numberFormat($otherCurrencyTotal, 2));
        }

        return $this->render('checkout', [
            'model' => $model,
            'can_place_order' => $canPlaceOrder,
            'balance' => $balance,
            'paygates' => $paygates,
            'checkoutForm' => $checkoutForm,
            'isOtherCurrency' => $isOtherCurrency,
            'otherCurrency' => $otherCurrency,
        ]);
    }

    public function actionCalculateBulk($id) 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = CartItem::findOne($id);
        $model->setScenario(CartItem::SCENARIO_CALCULATE_CART);
        $model->quantity = $request->post('quantity', 0);
        if ($model->validate()) {
            $amount = $model->getTotalPrice();
            $unit = $model->getTotalUnit();
            $unitName = $model->getUnitName();
            $origin = $model->getTotalOriginalPrice();
            return $this->asJson(['status' => true, 'data' => [
                'amount' => StringHelper::numberFormat($amount, 2),
                'origin' => StringHelper::numberFormat($origin, 2),
                'unit' => sprintf("%s %s", StringHelper::numberFormat($unit), strtoupper($unitName)),
            ]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return $this->asJson(['status' => false, 'errors' => $message]);
        }
    }

    public function actionBulk($id)
    {
        $request = Yii::$app->request;
        $items = $request->post();
        $model = new \website\forms\OrderPaymentBulkForm([
            'id' => $id,
            'items' => $items,
        ]);
        if ($model->validate() && $model->purchase()) {
            $errors = $model->getErrorList();
            $success = $model->getSuccessList();
            if (!count($success)) {
                return $this->asJson(['status' => false, 'errors' => 'Something went wroing']);
            } elseif (!count($errors)) {
                return $this->asJson(['status' => true, 'success' => 'Your orders are placed successfully']);
            } else {
                return $this->asJson(['status' => true, 'success' => 'Some of orders are placed successfully']);
            }
        } else {
            $errors = $model->getErrorSummary(true);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'errors' => $errors]);
        }
    }

    // New method to serve axios request
    public function actionCalculates($id) 
    {
        Yii::info('actionCalculate');
        $request = Yii::$app->request;
        // if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        // if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $model = CartItem::findOne($id);
        $model->setScenario(CartItem::SCENARIO_CALCULATE_CART);
        $model->quantity = $request->post('quantity');
        $model->currency = $request->post('currency');
        if ($model->validate()) {
            $totalPrice = $model->getTotalPrice();
            $subTotalPrice = $model->getSubTotalPrice();
            $promotionDiscount = $model->getPromotionDiscount();
            $unit = $model->getTotalUnit();
            $unitName = $model->getUnitName();
            $origin = $model->getTotalOriginalPrice();
            return $this->asJson(['status' => true, 'data' => [
                'sub-price' => StringHelper::numberFormat($subTotalPrice, 2),
                'promotion-discount' => StringHelper::numberFormat($promotionDiscount, 2),
                'amount' => StringHelper::numberFormat($totalPrice, 2),
                'origin' => StringHelper::numberFormat($origin, 2),
                'unit' => sprintf("%s %s", StringHelper::numberFormat($unit), strtoupper($unitName)),
            ]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return $this->asJson(['status' => false, 'errors' => $message]);
        }
    }

    public function actionCheckouts($id)
    {
        $request = Yii::$app->request;
        $items = $request->post('items');
        $paygate = $request->post('paygate');
        $model = new \website\forms\OrderPaymentBulkForm([
            'id' => $id,
            'items' => $items,
            'paygate' => $paygate
        ]);
        if ($model->validate() && $model->purchase()) {
            $errors = $model->getErrorList();
            $success = $model->getSuccessList();
            return $this->asJson(['status' => true, 'success' => reset($success), 'errors' => $errors]);
        } else {
            $errors = $model->getErrorSummary(true);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'errors' => $errors]);
        }
    }

    public function actionThankyou($id)
    {
        $order = Order::findOne($id);
        if (!$order) {
            throw new NotFoundHttpException('order does not exist.');
        }
        if ($order->customer_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('order does not exist.');
        }
        $viewUrl = Url::to(['order/index', '#' => $id]);
        $view = 'thankyou';
        if ($order->payment_type == 'online' && $order->payment_method != 'kinggems') {
            $view = $order->payment_method;
        }
        return $this->render($view, [
            'order' => $order,
            'viewUrl' => $viewUrl
        ]);
    }

    public function actionPaymentSuccess()
    {
        return $this->render('payment-success');
    }

    public function actionPaymentCoinBaseCallback()
    {
        Yii::info('start actionPaymentCoinBaseCallback');
        $identifier = 'coinbase';
        $config = \website\components\payment\PaymentGatewayFactory::getConfig($identifier);
        $paygate = \website\components\payment\PaymentGatewayFactory::getPaygate($config);
        return $paygate->processCharge();
    }

    public function actionPaymentCoinPaidCallback()
    {
        Yii::info('start actionPaymentCoinPaidCallback');
        $identifier = 'coinspaid';
        $config = \website\components\payment\PaymentGatewayFactory::getConfig($identifier);
        $paygate = \website\components\payment\PaymentGatewayFactory::getPaygate($config);
        return $paygate->processCharge();
    }

    public function actionPaymentWebmoneyCallback()
    {
        Yii::info('start actionPaymentWebmoneyCallback');
        $identifier = 'webmoney';
        $config = \website\components\payment\PaymentGatewayFactory::getConfig($identifier);
        $paygate = \website\components\payment\PaymentGatewayFactory::getPaygate($config);
        return $paygate->processCharge();
    }

    public function actionPaymentBinanceCallback()
    {
        Yii::info('start actionPaymentBinanceCallback');
        $identifier = 'binance';
        $config = \website\components\payment\PaymentGatewayFactory::getConfig($identifier);
        $paygate = \website\components\payment\PaymentGatewayFactory::getPaygate($config);
        return $paygate->processCharge();
    }
}