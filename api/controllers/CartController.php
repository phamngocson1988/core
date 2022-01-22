<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\data\Pagination;
use api\models\Game;
use api\components\cart\CartItem;

class CartController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCheckout($id)
    {
        $cart = Yii::$app->cart;
        $request = Yii::$app->request;
        $item = CartItem::findOne($id);
        $item->setScenario(CartItem::SCENARIO_ADD_CART);
        $item->quantity = $request->post('quantity');
        $item->username = $request->post('username');
        $item->password = $request->post('password');
        $item->character_name = $request->post('character_name');
        $item->recover_code = $request->post('recover_code');
        $item->server = $request->post('server');
        $item->note = $request->post('note');
        $item->login_method = $request->post('login_method');
        $amount = $request->post('amount');
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
        $checkoutForm = new \api\forms\OrderPaymentForm(['cart' => $cart]);
        if ($checkoutForm->validate() && $id = $checkoutForm->purchase()) {
            return ['status' => true, 'order_id' => $id];
        } else {
            $message = $checkoutForm->getErrorSummary(true);
            $message = reset($message);
            return [
                'status' => false,
                'error' => $message
            ];
        }
    }

}