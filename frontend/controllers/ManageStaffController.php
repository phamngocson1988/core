<?php
namespace frontend\controllers;

use Yii;
use frontend\models\OperatorReview;
use frontend\models\OperatorStaff;
use frontend\models\Complain;
use yii\web\UnauthorizedHttpException;
use yii\filters\VerbFilter;

class ManageStaffController extends ManageController
{
    protected $_actions = ['admin', 'sub-admin', 'moderator'];
    protected $_onlyAdminActions = ['revoke', 'assign'];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'revoke' => ['post'],
                'assign' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionAdmin()
    {
        $operator = $this->getOperator();
        $users = $operator->fetchStaff(OperatorStaff::ROLE_ADMIN);
        return $this->render('admin', [
            'operator' => $operator,
            'users' => $users
        ]);
    }

    public function actionSubAdmin()
    {
        $operator = $this->getOperator();
        $users = $operator->fetchStaff(OperatorStaff::ROLE_SUBADMIN);
        return $this->render('subadmin', [
            'operator' => $operator,
            'users' => $users,
            'role' => OperatorStaff::ROLE_SUBADMIN,
            'isAdmin' => $this->isAdmin(),
        ]);
    }

    public function actionModerator()
    {
        $operator = $this->getOperator();
        $users = $operator->fetchStaff(OperatorStaff::ROLE_MODERATOR);
        return $this->render('moderator', [
            'operator' => $operator,
            'users' => $users,
            'role' => OperatorStaff::ROLE_MODERATOR,
            'isAdmin' => $this->isAdmin(),
        ]);
    }

    public function actionRevoke() 
    {
        $request = Yii::$app->request;
        $form = new \frontend\forms\RevokeStaffForm([
            'doer_id' => Yii::$app->user->id,
            'victim_id' => $request->post('victim_id'),
            'role' => $request->post('role'),
            'operator_id' => $this->operator_id
        ]);
        if ($form->revoke()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $messages = $form->getFirstErrors();
            $message = reset($messages);
            throw new UnauthorizedHttpException($message, 1);
        }
    }
    public function actionAssign() 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $form = new \frontend\forms\AddStaffForm([
            'doer_id' => Yii::$app->user->id,
            'operator_id' => $this->operator_id
        ]);
        if ($form->load($request->post()) && $form->assign()) {
            return json_encode(['status' => true]);
        } else {
            $messages = $form->getFirstErrors();
            $message = reset($messages);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}