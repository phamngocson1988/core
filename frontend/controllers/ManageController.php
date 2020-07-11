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
    protected $operator_id;
    protected $_operator;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'edit', 'update-avatar', 'reply-review', 'reply-complain', 'review', 'list-review', 'complain', 'list-complain'],
                        'allow' => true,
                        'roles' => ['admin', 'manager', 'moderator'],
                    ],
                    
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) return false;
        $request = Yii::$app->request;
        $this->operator_id = $request->get('operator_id');
        $operator = $this->getOperator();
        return $operator;
    }

    protected function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->operator_id);
        }
        return $this->_operator;
    }

    public function actionIndex()
    {
        $operator = $this->getOperator();
        $id = $this->operator_id;
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
            'operator' => $operator,
            'review' => $review,
            'reviewForm' => $reviewForm,
            'complain' => $complain,
            'complainForm' => $complainForm,
        ]);
    }

    public function actionEdit()
    {
        $request = Yii::$app->request;
        $operator = $this->getOperator();
        $model = new \frontend\forms\UpdateOperatorForm(['id' => $this->operator_id]);
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
            'model' => $model,
            'operator' => $operator,
        ]);
    }

    public function actionUpdateAvatar() 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $operator = $this->getOperator();
        $operator->logo = $request->post('image_id');
        $operator->save();
        return $this->asJson(['status' => true]);
    }

    public function actionReview()
    {
        $operator = $this->getOperator();
        $user = Yii::$app->user->getIdentity();
        return $this->render('review', [
            'operator' => $operator,
            'user' => $user,
        ]);
    }

    public function actionListReview()
    {
        $request = Yii::$app->request;
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $operator = $this->getOperator();

        $command = OperatorReview::find()->where(['operator_id' => $operator->id])->offset($offset)->limit($limit);
        if ($request->get('sort') == 'date') {
            $type = $request->get('type', 'asc') == 'asc' ? SORT_ASC : SORT_DESC;
            $command->orderBy(['created_at' => $type]);
        }
        $status = $request->get('status');
        if ($status) {
            $not = $status == 'responded' ? 'NOT' : '';
            $command->andWhere(["IS $not", "reply", null]);
        }

        $models = $command->all();
        $total = $command->count();
        $html = $this->renderPartial('list-review', ['models' => $models]);
        return $this->asJson(['status' => true, 'data' => [
            'items' => $html,
            'total' => $total
        ]]);

    }

    public function actionReplyReview()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ReplyOperatorReviewForm([
            'user_id' => Yii::$app->user->id,
            'id' => $request->get('review_id')
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->reply()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'reply_review_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionComplain() 
    {
        $operator = $this->getOperator();
        return $this->render('complain', ['operator' => $operator]);
    }

    public function actionListComplain()
    {
        $operator = $this->getOperator();
        $request = Yii::$app->request;
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $command = Complain::find()->where(['operator_id' => $operator->id])->offset($offset)->limit($limit);

        if ($request->get('sort') == 'date') {
            $type = $request->get('type', 'asc') == 'asc' ? SORT_ASC : SORT_DESC;
            $command->orderBy(['created_at' => $type]);
        }
        $status = $request->get('status');
        if ($status) {
            $command->andWhere(["status" => $status]);
        }

        $complains = $command->all();
        $total = $command->count();

        $complainForm = new \frontend\forms\ReplyComplainForm();

        $html = $this->renderPartial('list-complain', [
            'operator' => $operator, 
            'complains' => $complains, 
            'complainForm' => $complainForm
        ]);
        return $this->asJson(['status' => true, 'data' => [
            'items' => $html,
            'total' => $total
        ]]);
    }

    public function actionReplyComplain()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ReplyComplainForm([
            'user_id' => Yii::$app->user->id,
            'complain_id' => $request->get('complain_id')
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