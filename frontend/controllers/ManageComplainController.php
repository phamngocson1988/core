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
use frontend\models\OperatorStaff;
use frontend\models\Complain;

class ManageComplainController extends ManageController
{
    protected $_actions = ['index', 'list', 'view', 'reply', 'assign'];
    protected $_onlyAdminActions = ['assign'];
    
    public function actionIndex() 
    {
        $request = Yii::$app->request;
        $operator = $this->getOperator();
        $condition = [];
        $condition['operator_id'] = $operator->id;
        if (!$this->isAdmin()) {
            $condition['managed_by'] = Yii::$app->user->id;
        }

        if (!$request->getIsAjax()) {
            $countComplain = Complain::find()
            ->where($condition)
            ->select(['status', 'COUNT(*) as count'])
            ->groupBy(['status'])
            ->asArray()
            ->all();
            $countByStatus = ArrayHelper::map($countComplain, 'status', 'count');
            return $this->render('index', ['operator' => $operator, 'countByStatus' => $countByStatus, 'isAdmin' => $this->isAdmin()]);
        } else {
            $offset = $request->get('offset', 0);
            $limit = $request->get('limit', 10);
            $command = Complain::find()->where($condition)->offset($offset)->limit($limit);

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

            $complainForm = new \frontend\forms\OperatorReplyComplainForm();

            $html = $this->renderPartial('_list', [
                'operator' => $operator, 
                'complains' => $complains, 
                'complainForm' => $complainForm,
                'isAdmin' => $this->isAdmin(),
            ]);
            return $this->asJson(['status' => true, 'data' => [
                'items' => $html,
                'total' => $total
            ]]);
        }
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        $operator = $this->getOperator();
        $complain = Complain::findOne($id);
        $reason = $complain->reason;
        $replies = $complain->replies;
        $complainForm = new \frontend\forms\OperatorReplyComplainForm();
        return $this->render('view', [
            'complain' => $complain,
            'operator' => $operator,
            'reason' => $reason,
            'replies' => $replies,
            'user' => $complain->user,
            'complainForm' => $complainForm,
            'canReply' => ($complain->managed_by == Yii::$app->user->id) || $this->isAdmin(),
            'isAdmin' => $this->isAdmin()
        ]);
    }

    public function actionReply()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\OperatorReplyComplainForm([
            'user_id' => Yii::$app->user->id,
            'complain_id' => $request->get('complain_id'),
            'operator_id' => $this->operator_id,
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->add()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'add_reply_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionAssign()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\AssignComplainForm([
            'user_id' => $request->post('user_id'),
            'complain_id' => $request->post('complain_id')
        ]);
        if ($model->validate() && $model->assign()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'assign_complain_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}