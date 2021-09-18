<?php

namespace website\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use website\models\OrderComplains;
use website\models\Order;

class MessageNotificationController extends Controller
{
    public function actionList()
    {
        $userId = Yii::$app->getUser()->getId();
        $complainTable = OrderComplains::tableName();
        $orderTable = Order::tableName();
        $list = OrderComplains::find()
        ->innerJoin($orderTable, "{$orderTable}.id = {$complainTable}.order_id")
        ->where([
            "{$orderTable}.customer_id" => $userId,
            "{$complainTable}.is_customer" => OrderComplains::IS_NOT_CUSTOMER,
            "{$complainTable}.is_read" => 0
        ])
        ->orderBy(["{$complainTable}.created_at" => SORT_DESC])
        ->select(["{$complainTable}.*"])
        ->asArray()
        ->limit(10)
        ->all();

        $notifs = $this->prepareNotifications($list);
        return $this->ajaxResponse(['list' => $notifs]);
    }

    public function actionRead($id)
    {
        $model = OrderComplains::findOne($id);
        if ($model) {
            $model->is_read = 1;
            $model->save();
        }
        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }
        return $this->asJson(['status' => true]);
    }

    public function actionCount()
    {
        $userId = Yii::$app->getUser()->getId();
        $complainTable = OrderComplains::tableName();
        $orderTable = Order::tableName();
        $count = OrderComplains::find()
        ->innerJoin($orderTable, "{$orderTable}.id = {$complainTable}.order_id")
        ->where(["{$orderTable}.customer_id" => $userId])
        ->andWhere(["{$complainTable}.is_customer" => OrderComplains::IS_NOT_CUSTOMER])
        ->andWhere(["{$complainTable}.is_read" => 0])
        ->count();
        $this->ajaxResponse(['count' => $count]);
    }

    private function prepareNotifications($list){
        $notifs = [];
        $seen = [];
        foreach($list as $notif){
            $route = ['order/index', '#' => $notif['order_id']];
            $notif['url'] = !empty($route) ? Url::to($route) : '';
            $notif['read'] = $notif['is_read'];
            $notif['key'] = $notif['id'];
            $notif['class'] = $notif['object_name'];
            $notif['order_id'] = $notif['order_id'];
            $notif['message'] = $notif['content_type'] == 'image' ? 'An image was sent to you' : $notif['content'];
            $notif['timeago'] = \common\components\helpers\TimeElapsed::timeElapsed($notif['created_at']);
            $notifs[] = $notif;
        }

        return $notifs;
    }

    public function ajaxResponse($data = [])
    {
        if(is_string($data)){
            $data = ['html' => $data];
        }

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes(true);
        foreach ($flashes as $type => $message) {
            $data['notifications'][] = [
                'type' => $type,
                'message' => $message,
            ];
        }
        return $this->asJson($data);
    }

    public function actionReadAll()
    {
        $userId = Yii::$app->getUser()->getId();
        $orderTable = Order::tableName();
        $complainTable = OrderComplains::tableName();
        $records = OrderComplains::find()
        ->innerJoin($orderTable, "{$orderTable}.id = {$complainTable}.order_id")
        ->where(["{$orderTable}.customer_id" => $userId])
        ->andWhere(["{$complainTable}.is_customer" => OrderComplains::IS_NOT_CUSTOMER])
        ->andWhere(["{$complainTable}.is_read" => 0])
        ->select(["{$complainTable}.id"])->all();
        $ids = ArrayHelper::getColumn($records, 'id');
        OrderComplains::updateAll(['is_read' => 1], ['in', 'id', $ids]);
        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }
        die('404');
    }

}
