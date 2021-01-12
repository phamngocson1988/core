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
}