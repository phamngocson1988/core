<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use api\models\Order;

class OrderController extends Controller
{
	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBearerAuth::className(),
	    ];
	    $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'cancel' => ['post'],
                'send-complain' => ['post'],
                'list-complain' => ['get'],
                'move-to-confirmed' => ['post'],
                'create' => ['post'],
                'pay' => ['post'],
            ],
        ];
	    return $behaviors;
	}

	public function actionView($id)
	{
		$order = Order::findOne($id);
		if (!$order) {
            throw new NotFoundHttpException('order does not exist.');
        }
        if ($order->customer_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('order does not exist.');
        }
		return $order;
	}

	public function actionCancel($id)
    {
        $request = Yii::$app->request;
        $model = new \api\forms\CancelOrderForm(['id' => $id]);
        if ($model->validate() && $model->cancel()) {
            return ['status' => true];
        } else {
            $message = $model->getFirstErrors();
            $message = reset($message);
            return [
                'status' => false,
                'error' => $message
            ];
        }
    }

    public function actionSendComplain($id)
    {
        $request = Yii::$app->request;
        $content = $request->post('content');
        $ouath_sublink_client_id = $request->post('ouath_sublink_client_id');
        $user_sublink_id = $request->post('user_sublink_id');
        $form = new \api\forms\CreateOrderComplainForm([
            'id' => $id, 
            'content' => $content,
            'ouath_sublink_client_id' => $ouath_sublink_client_id,
            'user_sublink_id' => $user_sublink_id,
        ]);
        if (!$form->create()) {
            $message = $form->getFirstErrors();
            $message = reset($message);
            return [
                'status' => false,
                'error' => $message
            ];
        }
        return ['status' => true];
    }

    public function actionListComplain($id)
    {
        $form = new \api\forms\ListOrderComplainForm(['id' => $id]);
        $list = $form->fetch();
        if ($list === false) {
            $message = $form->getFirstErrors();
            $message = reset($message);
            return [
                'status' => false,
                'error' => $message
            ];
        }
        return ['status' => true, 'data' => $list];
    }

    public function actionMoveToConfirmed()
    {
        $request = Yii::$app->request;
        $id = $request->post('id');
        $model = new \api\forms\ConfirmOrderForm(['id' => $id]);
        if ($model->save()) {
            return $this->asJson(['status' => true]);
        } else {
            $errors = $model->getErrorSummary(false);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'errors' => $error]);
        }
    }

    public function actionCreate($id)
    {
        $cart = Yii::$app->cart;
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $item = \api\components\cart\CartItem::findOne($id);
        $item->setScenario(\api\components\cart\CartItem::SCENARIO_ADD_CART);
        $item->quantity = $request->post('quantity');
        $item->username = $request->post('username');
        $item->password = $request->post('password');
        $item->character_name = $request->post('character_name');
        $item->recover_code = $request->post('recover_code');
        $item->server = $request->post('server');
        $item->note = $request->post('note');
        $item->login_method = $request->post('login_method');
        if (!$item->validate()) {
            $message = $item->getErrorSummary(true);
            $message = reset($message);
            return [
                'status' => false,
                'error' => $message
            ];
        }

        // Add cart
        $cart = Yii::$app->cart;
        $cart->clear();
        $cart->add($item);

        // Checkout
        $checkoutForm = new \common\forms\CreateOrderForm(['cart' => $cart, 'user_ip' => $request->userIP]);
        $id = $checkoutForm->purchase();
        if (!$id) {
            $error = $checkoutForm->getFirstErrorMessage();
            return ['status' => false, 'error' => $error];
        }
        $amount = $request->post('amount');
        $payer = $request->post('payer');
        $result = ['status' => true, 'order_id' => $id];
        if ($amount) {
            $order = Order::findOne($id);
            $userId = Yii::$app->user->id;
            $order->generatePaymentToken(['amount' => $amount, 'payer' => $payer, 'user_id' => $userId]);
            $order->save();
            $generateLinkForm = new \common\forms\GenerateResellerCodeForm(['user_id' => $userId]);
            $code = $generateLinkForm->generate();
            if ($code) {
                $paymentLink = sprintf('%s/%s.html?token=%s', Yii::$app->params['payment_url'], $code, $order->payment_token);
                $result['payment_link'] = $paymentLink;
            }
        }
        return $this->asJson($result);
    }

    public function actionPay($id)
    {
        $purchaseForm = new \common\forms\PurchaseOrderByWalletForm([
            'order_id' => $id,
            'user_id' => Yii::$app->user->id
        ]);
        if (!$purchaseForm->run()) {
            $error = $purchaseForm->getFirstErrorMessage();
            return $this->asJson(['status' => false, 'error' => $error]);
        }
        return $this->asJson(['status' => true]);
    }
}