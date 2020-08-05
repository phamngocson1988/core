<?php
namespace frontend\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use webzop\notifications\controllers\DefaultController;
use common\components\helpers\TimeElapsed;

class NotificationController extends DefaultController
{
    public function actionList()
    {
        $userId = Yii::$app->getUser()->getId();
        $list = (new Query())
            ->from('{{%notifications}}')
            ->andWhere(['or', 'user_id = 0', 'user_id = :user_id'], [':user_id' => $userId])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
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
            $notif['timeago'] = TimeElapsed::timeElapsed($notif['created_at']);
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
