<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use supplier\models\OrderComplains;

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
        ->andWhere(['OR',
           ["IN", "object_name", [OrderComplains::OBJECT_NAME_CUSTOMER, OrderComplains::OBJECT_NAME_ADMIN]],
           ["created_by" => Yii::$app->user->id]
       ])
        ->with('sender')->all();
        
        $list = array_map(function($model) {
            $sender = $model->sender;
            $senderName = ($model->isSupplier() && $model->created_by == Yii::$app->user->id) ? $sender->name : 'Admin';

            $object = [];
            $object['id'] = $model->id;
            $object['content_type'] = $model->content_type;
            $object['avatar'] = $sender->getAvatarUrl(null, null);
            $object['senderName'] = $senderName;
            $object['content'] = nl2br($model->content);
            $object['created_at'] = \common\components\helpers\TimeElapsed::timeElapsed($model->created_at);
            return $object;
        }, $models);
        return $this->asJson(['list' => $list]);
    }
}
