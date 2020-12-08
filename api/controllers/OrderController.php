<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use api\models\Order;

class OrderController extends Controller
{
	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBearerAuth::className(),
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
}