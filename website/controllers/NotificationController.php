<?php

namespace website\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use webzop\notifications\controllers\DefaultController;

class NotificationController extends DefaultController
{

    public function actionList()
    {
        $userId = Yii::$app->getUser()->getId();
        $list = (new Query())
            ->from('{{%notifications}}')
            ->andWhere(['or', 'user_id = 0', 'user_id = :user_id'], [':user_id' => $userId])
            ->andWhere(['read' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->limit(3)
            ->all();
        $notifs = $this->prepareNotifications($list);
        return $this->ajaxResponse(['list' => $notifs]);
    }

    public function actionDelete($id)
    {
        Yii::$app->getDb()->createCommand()->delete('{{%notifications}}', 'id = :id', [':id' => $id])->execute();

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
            $notif['dispatch'] = Url::to(['notification/dispatch', 'id' => $notif['id']]);
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
        Yii::$app->getDb()->createCommand()->update('{{%notifications}}', ['read' => true, 'seen' => true])->execute();
        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }
        die('404');
    }

    public function actionRead($id)
    {
        Yii::$app->getDb()->createCommand()->update('{{%notifications}}', ['read' => true, 'seen' => true], ['id' => $id])->execute();

        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }
        die('404');
    }

    public function actionCount()
    {
        $userId = Yii::$app->getUser()->getId();
        $count = (new Query())
            ->from('{{%notifications}}')
            ->andWhere(['or', 'user_id = 0', 'user_id = :user_id'], [':user_id' => $userId])
            ->andWhere(['seen' => false])
            ->count();

        $this->ajaxResponse(['count' => $count]);
    }

    public function actionDispatch($id)
    {
        $userId = Yii::$app->user->id;
        $list = (new Query())
        ->from('{{%notifications}}')
        ->andWhere(['or', 'user_id = 0', 'user_id = :user_id'], [':user_id' => $userId])
        ->andWhere(['id' => $id])
        ->all();

        if (count($list)) {
            $notifications = $this->prepareNotifications($list);
            $notification = reset($notifications);
            return $this->redirect($notification['url']);
        }
        return $this->redirect(['site/index']);
    }
}
