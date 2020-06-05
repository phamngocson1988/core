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

class OperatorController extends Controller
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
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['add-favorite'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    
                ],
            ],
        ];
    }

    public function actionIndex()
    {

    }

    public function actionView($id, $slug) 
    {
        $model = Operator::findOne($id);
        if (!$model) throw new NotFoundHttpException(Yii::t('app', 'operator_not_found'));

        $user = Yii::$app->user->getIdentity();
        $isFavorite = $user && $user->isOperatorFavorite($model->id);
        $reviewForm = new \frontend\forms\AddOperatorReviewForm();
        return $this->render('view', [
            'model' => $model,
            'isFavorite' => $isFavorite,
            'user' => $user,
            'reviewForm' => $reviewForm
        ]);
    }

    public function actionAddFavorite()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\AddOperatorFavoriteForm([
            'user_id' => Yii::$app->user->id,
            'operator_id' => $request->get('id')
        ]);
        if ($model->validate() && $model->add()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'add_operator_favorite_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionAddReview()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\AddOperatorReviewForm([
            'user_id' => Yii::$app->user->id,
            'operator_id' => $request->get('id')
        ]);
        if ($model->validate() && $model->add()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'add_operator_favorite_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}