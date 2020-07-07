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

use frontend\models\Operator;
use frontend\models\OperatorFavorite;
use frontend\models\OperatorReview;
use frontend\models\Complain;

class ManageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'edit', 'update-avatar', 'reply-review', 'reply-complain'],
                        'allow' => true,
                        'roles' => ['admin', 'manager', 'moderator'],
                    ],
                    
                ],
            ],
        ];
    }

    public function actionIndex($id, $slug)
    {
        $model = Operator::findOne($id);
        // review
        $review = OperatorReview::find()
        ->where(['operator_id' => $id])
        ->andWhere(['IS', 'reply', null])
        ->one();
        $reviewForm = new \frontend\forms\ReplyOperatorReviewForm(); 

        // complain
        $complain = Complain::find()
        ->where([
            'operator_id' => $id,
            'status' => Complain::STATUS_OPEN
        ])
        ->one();
        $complainForm = new \frontend\forms\ReplyComplainForm();
        return $this->render('index', [
            'model' => $model,
            'review' => $review,
            'reviewForm' => $reviewForm,
            'complain' => $complain,
            'complainForm' => $complainForm,
        ]);
    }

    public function actionEdit($id, $slug)
    {
        $request = Yii::$app->request;
        $model = new \frontend\forms\UpdateOperatorForm(['id' => $id]);
        if ($model->load($request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData();
        }
        return $this->render('edit', [
            'model' => $model
        ]);
    }

    public function actionUpdateAvatar($id) 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $operator = Operator::findOne($id);
        $operator->logo = $request->post('id');
        $operator->save();
        return $this->asJson(['status' => true]);
    }

    public function actionReplyReview()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ReplyOperatorReviewForm([
            'user_id' => Yii::$app->user->id,
            'id' => $request->get('id')
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->reply()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'reply_review_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionReplyComplain($id)
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