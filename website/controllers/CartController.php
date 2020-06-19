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

        $model = new CartItem(['game_id' => $id]);
        $model->setScenario(CartItem::SCENARIO_CALCULATE_CART);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $amount = $model->getTotalPrice();
            return $this->asJson(['status' => true, 'data' => ['amount' => number_format($amount, 1)]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return $this->asJson(['status' => false, 'errors' => $message]);
        }
    }

    public function actionAdd($id) 
    {
        $request = Yii::$app->request;

        $model = new CartItem(['game_id' => $id]);
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
        $game = $model->getGame();
        $model->setScenario(CartItem::SCENARIO_UPDATE_CART);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $cart = Yii::$app->cart;
            $cart->clear();
            $cart->add($model);
            return $this->redirect(['cart/checkout']);
        }
        return $this->render('index', [
            'model' => $model,
            'game' => $game,
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
            Yii::$app->session->setFlash('error', $checkoutForm->getErrors());
        }
        $model = $cart->getItem();
        $game = $model->getGame();
        $user = Yii::$app->user->getIdentity();
        $balance = $user->getWalletAmount();
        $canPlaceOrder = $balance >= $cart->getTotalPrice();
        $paygates = Paygate::find()->where(['status' => Paygate::STATUS_ACTIVE])->all();

        return $this->render('checkout', [
            'model' => $model,
            'game' => $game,
            'can_place_order' => $canPlaceOrder,
            'balance' => $balance,
            'paygates' => $paygates,
            'checkoutForm' => $checkoutForm,
        ]);
    }
}