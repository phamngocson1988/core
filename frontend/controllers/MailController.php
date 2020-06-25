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
        return $this->render('index');
    }

    public function actionCompose()
    {
        $request = Yii::$app->request;
        $model = new \frontend\forms\ComposeMailForm([
            'from' => Yii::$app->user->id,
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
        $replies = $complain->replies;
        $replyForm = new \frontend\forms\ReplyComplainForm();
        $complains = Complain::find()->where(['operator_id' => $operator->id])->limit(4)->all();

        $user = Yii::$app->user;
        $canReply = false;
        if (!$user->isGuest) {
            $canReply = $complain->isOpen() && $user->id == $complain->user_id;
        }
        return $this->render('view', [
            'complain' => $complain,
            'operator' => $operator,
            'reason' => $reason,
            'user' => $complain->user,
            'replies' => $replies,
            'replyForm' => $replyForm,
            'complains' => $complains,
            'canReply' => $canReply,
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

    public function actionOperator($id, $slug)
    {
        $operator = Operator::findOne($id);
        $command = Complain::find()->where(['operator_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $complains = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('operator', [
            'complains' => $complains,
            'pages' => $pages,
            'operator' => $operator,
        ]);
    }

    public function actionFollow($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\FollowComplainForm([
            'user_id' => Yii::$app->user->id,
            'complain_id' => $id
        ]);
        if ($model->validate() && $model->follow()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionUnfollow($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\FollowComplainForm([
            'user_id' => Yii::$app->user->id,
            'complain_id' => $id
        ]);
        if ($model->validate() && $model->unfollow()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}