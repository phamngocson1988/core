<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;

class WalletController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'wallet.index';
    	return $this->render('index');
    }

    public function actionCart() 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'errors' => 'You need to login']);

        // $model = new LoginForm();
        // if ($model->load(Yii::$app->request->post()) && $model->login()) {
        //     return json_encode(['status' => true, 'user_id' => Yii::$app->user->id]);
        // } else {
        //     $message = $model->getErrorSummary(true);
        //     $message = reset($message);
        //     return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        // }
        return $this->asJson(['status' => true, 'data' => $request->post()]);
    }

    public function actionPurchase()
    {
        $this->view->params['main_menu_active'] = 'wallet.index';
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $settings = Yii::$app->settings;
        $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);
        $identifier = $request->post('identifier');
        if (!$identifier) throw new InvalidParamException('You must choose a payment gateway');
        $paymentCart = new PaymentCart([
            'title' => 'Pay for buying Kingcoin',
        ]);
        $cart = Yii::$app->kingcoin;
        if (!$cart->getItems()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty');
            return $this->redirect(['topup/index']);
        }
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
        $totalPrice = $cart->getTotalPrice();
        // Save transaction
        $trn = new PaymentTransaction();
        $trn->user_id = $user->id;
        $trn->user_ip = $request->userIP;
        $trn->payment_method = $identifier;
        $trn->payment_type = $gateway->type;
        $trn->rate_usd = $rate;
        // $trn->payment_id = $gateway->getReferenceId();
        // Price
        $trn->price = $cart->getSubTotalPrice();
        $trn->discount_price = $cart->hasPromotion() ? $cart->getPromotionMoney() : 0;
        $trn->total_price = $totalPrice;
        $trn->total_fee = $gateway->getFee($totalPrice);
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
            $trn->promotion_id = $promotion->id;
        }
        $trn->save();
        $gateway->setReferenceId($trn->auth_key);
        $cart->clear();

        // Notify saler in case this is offline payment
        if ($gateway->type == 'offline') {
            $trn->attachBehavior('notification', DepositNotificationBehavior::className());
            $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
            $trn->pushNotification(DepositNotification::NOTIFY_SALER_NEW_ORDER, $salerTeamIds);
        }

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

}