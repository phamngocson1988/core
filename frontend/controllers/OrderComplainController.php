<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use frontend\models\OrderComplains;

class OrderComplainController extends Controller
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

    public function actionList($id)
    {
        $request = Yii::$app->request;
        $models = OrderComplains::find()->where(['order_id' => $id])
        ->with('sender')->all();
        $list = array_map(function($model) {
            $sender = $model->sender;
            // $complainClass = $model->isCustomer() ? 't-report-me' : 't-report-you';
            $senderName = $model->isCustomer() ? Yii::$app->user->identity->name : 'Admin';
            // $complainDate = date('Y-m-d') == date('Y-m-d', strtotime($complain->created_at)) ? date('H:i A', strtotime($complain->created_at)) : date('d-m-Y, H:i', strtotime($complain->created_at));


            $object = [];
            $object['id'] = $model->id;
            $object['avatar'] = $sender->getAvatarUrl(null, null);
            $object['senderName'] = $senderName;
            $object['content'] = nl2br($model->content);
            $object['content_type'] = $model->content_type;
            $object['created_at'] = \common\components\helpers\TimeElapsed::timeElapsed($model->created_at);
            $object['is_customer'] = $model->isCustomer();
            return $object;
        }, $models);
        return $this->asJson(['list' => $list]);
    }
}
