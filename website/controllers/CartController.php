<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use website\components\cart\CartItem;
use website\models\Paygate;
use website\models\Order;

class CartController extends Controller
{
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
                'amount' => number_format($amount, 1),
                'origin' => number_format($origin, 1),
                'unit' => sprintf("%s %s", number_format($unit), strtoupper($unitName)),
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
            return $this->redirect(['order/index', '#' => $id]);
        } else {
            Yii::$app->session->setFlash('error', $checkoutForm->getErrorSummary(true));
        }
        $model = $cart->getItem();
        $user = Yii::$app->user->getIdentity();
        $balance = $user->getWalletAmount();
        $canPlaceOrder = $balance >= $cart->getTotalPrice();
        $paygates = Paygate::find()->where(['status' => Paygate::STATUS_ACTIVE])->all();

        return $this->render('checkout', [
            'model' => $model,
            'can_place_order' => $canPlaceOrder,
            'balance' => $balance,
            'paygates' => $paygates,
            'checkoutForm' => $checkoutForm,
        ]);
    }
}