<?php

namespace website\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;

class PushNotificationController extends Controller
{

    public function actionList()
    {
        $userId = Yii::$app->getUser()->getId();
        $list = (new Query())
            ->from('{{%device_notifications}}')
            ->andWhere(['or', 'user_id = 0', 'user_id = :user_id'], [':user_id' => $userId])
            ->andWhere(['>=', 'created_at', strtotime('-10 minutes')])
            ->orderBy(['id' => SORT_DESC])
            ->limit(3)
            ->all();
        $notifs = $this->prepareNotifications($list);
        return $this->ajaxResponse(['list' => $notifs]);
    }

    public function actionDelete($id)
    {
        Yii::$app->getDb()->createCommand()->delete('{{%device_notifications}}', 'id = :id', [':id' => $id])->execute();

        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }

        return $this->asJson(['status' => true]);
    }

    private function prepareNotifications($list){
        $notifs = [];
        $seen = [];
        foreach($list as $notif){
            $route = @unserialize($notif['route']);
            $notif['url'] = !empty($route) ? Url::to($route) : '';
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
}
