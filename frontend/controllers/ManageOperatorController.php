<?php
namespace frontend\controllers;

use Yii;
use frontend\models\OperatorReview;
use frontend\models\OperatorStaff;
use frontend\models\Complain;

class ManageOperatorController extends ManageController
{
    protected $_actions = ['index', 'edit', 'update-avatar'];
    protected $_onlyAdminActions = ['edit', 'update-avatar'];

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

        $user = Yii::$app->user->identity;
        return $this->render('index', [
            'operator' => $operator,
            'review' => $review,
            'reviewForm' => $reviewForm,
            'complain' => $complain,
            'complainForm' => $complainForm,
            'isAdmin' => $this->isAdmin()
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
            'isAdmin' => $this->isAdmin()
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
}