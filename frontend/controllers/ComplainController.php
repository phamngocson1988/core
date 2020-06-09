<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\models\Complain;

class ComplainController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create', 'reply'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(Url::to(['complain/create']));
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new \frontend\forms\CreateComplainForm([
            'user_id' => Yii::$app->user->id,
            'operator_id' => $request->get('operator_id')
        ]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['site/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        $complain = Complain::findOne($id);
        $operator = $complain->operator;
        $reason = $complain->reason;
        $user = $complain->user;
        $replies = $complain->replies;
        $replyForm = new \frontend\forms\ReplyComplainForm();
        $complains = Complain::find()->where(['operator_id' => $operator->id])->limit(4)->all();
        return $this->render('view', [
            'complain' => $complain,
            'operator' => $operator,
            'reason' => $reason,
            'user' => $user,
            'replies' => $replies,
            'replyForm' => $replyForm,
            'complains' => $complains,
        ]);
    }

    public function actionReply($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ReplyComplainForm([
            'user_id' => Yii::$app->user->id,
            'complain_id' => $request->get('id')
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->add()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'add_reply_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}