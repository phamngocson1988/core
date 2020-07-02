<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\models\MailThread;
use frontend\models\Mail;

class MailController extends Controller
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

    public function actionIndex()
    {
        $form = new \frontend\forms\FetchMailBoxForm([
            'user_id' => Yii::$app->user->id
        ]);
        $command = $form->getCommand();
        $threads = $command->limit(50)->all();
        return $this->render('index', [
            'threads' => $threads
        ]);
    }

    public function actionCompose()
    {
        $request = Yii::$app->request;
        $model = new \frontend\forms\ComposeMailForm([
        ]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->compose()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['mail/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        return $this->render('compose', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        $thread = MailThread::findOne($id);
        $mails = Mail::find()->where(['mail_thread_id' => $id])->orderBy(['created_at' => SORT_ASC])->all();

        $model = new \frontend\forms\ReplyMailForm([
            'thread_id' => $id
        ]);

        return $this->renderPartial('view', [
            'thread' => $thread,
            'mails' => $mails,
            'model' => $model,
        ]);
    }

    public function actionReply($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ReplyMailForm([
            'thread_id' => $id
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->reply()) {
            return json_encode([
                'status' => true, 
                'data' => [
                    'message' => Yii::t('app', 'add_reply_success'),
                    'id' => $id
                ]
            ]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}