<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\base\ViewNotFoundException;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Package;
use common\models\UserWallet;
use common\models\PaymentTransaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\components\kingcoin\CartPromotion;
use frontend\components\kingcoin\CartItem;


use frontend\components\payment\cart\PaymentItem;
use frontend\components\payment\cart\PaymentCart;
use frontend\components\payment\cart\PaymentPromotion;
use frontend\components\payment\PaymentGateway;
use frontend\components\payment\PaymentGatewayFactory;

/**
 * TopupController
 */
class TopupController extends Controller
{
    public function beforeAction($action)
    {            
        if ($action->id == 'verify') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->view->params['body_class'] = 'global-bg';
        $request = Yii::$app->request;
        $items = CartItem::find()->indexBy('id')->all();
        
        

    	return $this->render('index', [
            // 'package' => $package,
            'items' => $items
        ]);
    }

    public function actionAdd($id)
    {
        $request = Yii::$app->request;
        $package = CartItem::findOne($id);
        if (!$package) throw new BadRequestHttpException('Can not find the package');
        $package->setScenario(CartItem::SCENARIO_ADD_CART);
        if ($package->load($request->post()) && $package->validate()) {
            if ($request->isAjax) {
                return $this->asJson(['status' => true, 'data' => [
                    'price' => $package->getTotalPrice(),
                    'coin' => $package->getTotalCoin()
                ]]);
            }
            $cart = Yii::$app->kingcoin;
            $cart->clear();
            $cart->add($package);
            return $this->redirect(['topup/confirm']);
        } else {
            if ($request->isAjax) {
                return $this->asJson(['status' => false, 'package' => $package, 'error' => $package->getErrorSummary(true)]);
            }
            Yii::$app->session->setFlash('error', $package->getErrorSummary(true));
            return $this->redirect(['topup/index']);
        }
    }

    public function actionConfirm()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->kingcoin;
        $promotion_code = $request->post('promotion_code');
        $item = $cart->getItem();
        if (!$item) return $this->redirect(['site/index']);
        $item->setScenario(CartItem::SCENARIO_EDIT_CART);
        if ($request->isPost) {
            if ($item->load($request->post()) && $item->validate()) {
                $cart->add($item);
                $discount = CartPromotion::findOne([
                    'code' => $promotion_code,
                    'promotion_scenario' => CartPromotion::SCENARIO_BUY_COIN,
                ]);

                if ($discount) {
                    $discount->setScenario(CartPromotion::SCENARIO_ADD_PROMOTION);
                    $discount->user_id = Yii::$app->user->id;
                    $discount->game_id = $item->id;
                    if (!$discount->validate() || !$discount->code) $cart->removePromotionItem();                            
                    else {
                        $cart->setPromotionItem($discount);
                        $cart->applyPromotion();
                    }
                } else {
                    $cart->removePromotionItem();
                }
            }
        }

        return $this->render('confirm', [
            'cart' => $cart,
            'promotion_code' => $promotion_code,
            'item' => $item
        ]);
    }

    public function actionCheckout()
    {

        $cart = Yii::$app->kingcoin;
        if (!$cart->getItem()) throw new NotFoundHttpException("You have not added any pricing package", 1);
        return $this->render('checkout');
    }

    public function actionPurchase()
    {
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $identifier = $request->post('identifier');
        if (!$identifier) throw new InvalidParamException('You must choose a payment gateway');
        $paymentCart = new PaymentCart([
            'title' => 'Pay for buying Kingcoin',
        ]);
        $cart = Yii::$app->kingcoin;
        if (!$cart->getItems()) return $this->redirect(['site/index']);
        $cartItem = $cart->getItem();
        $paymentCartItem = new PaymentItem([
            'id' => $cartItem->getUniqueId(),
            'title' => $cartItem->getLabel(),
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->getPrice()
        ]);
        $paymentCart->addItem($paymentCartItem);
        
        if ($cart->hasPromotion()) {
            $cart->applyPromotion();
            $promotionItem = $cart->getPromotionItem();
            $paymentPromotion = new PaymentPromotion([
                'id' => $promotionItem->code,
                'title' => 'promotion promotion code ' . $promotionItem->code,
                'price' => $cart->getPromotionMoney()
            ]);
            $paymentCart->setPromotion($paymentPromotion);
        }
        $gateway = PaymentGatewayFactory::getClient($identifier);

        // Save transaction
        $trn = new PaymentTransaction();
        $trn->user_id = $user->id;
        $trn->payment_method = $identifier;
        $trn->payment_id = $gateway->getReferenceId();
        // Price
        $trn->price = $cart->getSubTotalPrice();
        $trn->discount_price = $cart->hasPromotion() ? $cart->getPromotionMoney() : 0;
        $trn->total_price = $cart->getTotalPrice();
        // Coin
        $trn->coin = $cart->getSubTotalCoin();
        $trn->promotion_coin = $cart->getPromotionCoin();
        $trn->total_coin = $cart->getTotalCoin();
        $trn->description = $gateway->identifier;
        $trn->created_by = $user->id;
        $trn->status = PaymentTransaction::STATUS_PENDING;
        $trn->payment_at = date('Y-m-d H:i:s');
        $trn->generateAuthKey();
        if ($cart->hasPromotion()) {
            $promotion = $cart->getPromotionItem();
            $trn->promotion_code = $promotion->code;
        }
        $trn->save();
        $cart->clear();
        $gateway->setCart($paymentCart);
        $paygateData = $gateway->request();
        try {
            return $this->render($identifier, [
                'paygateData' => (array)$paygateData,
                'transaction' => $trn 
            ]);
        } catch (ViewNotFoundException $e) {
            return;
        }
    }

    public function actionVerify($identifier)
    {
        $gateway = PaymentGatewayFactory::getClient($identifier);
        try {
            if ($gateway->confirm()) {
                $refId = $gateway->getReferenceId();
                $user = Yii::$app->user->getIdentity();
                $trn = PaymentTransaction::find()->where([
                    'payment_id' => $refId, 
                    'status' => PaymentTransaction::STATUS_PENDING
                ])->one();
                if (!$trn) throw new Exception("Can not find out the transaction #$refId");
                $trn->status = PaymentTransaction::STATUS_COMPLETED;
                $trn->payment_at = date('Y-m-d H:i:s');
                $trn->save();
        
                $wallet = new UserWallet();
                $wallet->coin = $trn->total_coin;
                $wallet->balance = $user->getWalletAmount() + $wallet->coin;
                $wallet->type = UserWallet::TYPE_INPUT;
                $wallet->description = "Transaction #$trn->auth_key";
                $wallet->ref_name = PaymentTransaction::className();
                $wallet->ref_key = $trn->auth_key;
                $wallet->created_by = $user->id;
                $wallet->user_id = $user->id;
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->save();
                return $gateway->doSuccess();
            } else {
                return $gateway->doError();
            }
        } catch (Exception $e) {
            Yii::error($gateway->identifier . $gateway->getReferenceId() . " confirm error " . $e->getMessage());
            return $gateway->doError();
        }
    }

    public function actionSuccess()
    {
        // $this->layout = 'notice';
        return $this->render('/site/notice', [
            'title' => 'You have just bought a pricing successfully.',
            'content' => 'Congratulations!!! Now your wallet is full of King Coins.'
        ]);
    }

    public function actionError()
    {
        die('error');
    }

    public function actionCancel($identifier)
    {
        $gateway = PaymentGatewayFactory::getClient($identifier);
        $gateway->cancel();
        try {
            $refId = $gateway->getReferenceId();
            $trn = PaymentTransaction::find()->where([
                'payment_id' => $refId, 
                'status' => PaymentTransaction::STATUS_PENDING
            ])->one();
            if ($trn) $trn->delete();
            die('You have cancelled the order successfully');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 1);
        }
    }
}