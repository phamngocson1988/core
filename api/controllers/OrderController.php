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
}