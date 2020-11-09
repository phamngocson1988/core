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
use common\components\helpers\StringHelper;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['calculate', 'add'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'checkout', 'bulk', 'calculate-bulk', 'thankyou'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }

    public function actionCalculate($id) 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = CartItem::findOne($id);
        $model->setScenario(CartItem::SCENARIO_CALCULATE_CART);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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

    public function actionCheckout()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->cart;
        
        $checkoutForm = new \website\forms\OrderPaymentForm(['cart' => $cart]);
        if ($checkoutForm->load($request->post()) && $checkoutForm->validate() && $id = $checkoutForm->purchase()) {
            // return $this->redirect(['order/index', '#' => $id]);
            return $this->redirect(['cart/thankyou', 'id' => $id]);
        } else {
            Yii::$app->session->setFlash('error', $checkoutForm->getErrorSummary(true));
        }
        $model = $cart->getItem();
        $user = Yii::$app->user->getIdentity();
        $balance = $user->getWalletAmount();
        $canPlaceOrder = $balance >= $cart->getTotalPrice();
        $paygates = Paygate::find()->where(['status' => Paygate::STATUS_ACTIVE])->all();
        $isOtherCurrency = $model->currency != 'USD';
        $otherCurrency = '';
        if ($isOtherCurrency) {
            $usdCurrency = Currency::findOne('USD');
            $otherCurrencyTotal = Currency::convertUSDToCurrency($model->getTotalPrice(), $model->currency);
            $currencyModel = Currency::findOne($model->currency);
            $otherCurrency = $currencyModel->addSymbolFormat(number_format($otherCurrencyTotal, 1));
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
        return $this->render('thankyou', [
            'order' => $order,
            'viewUrl' => $viewUrl
        ]);
    }
}