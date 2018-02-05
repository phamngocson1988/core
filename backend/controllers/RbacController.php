<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use yii\base\InvalidParamException;
use backend\forms\CreateRoleForm;
use backend\forms\AssignRoleForm;
use yii\helpers\Url;

class RbacController extends Controller
{
    public function actionInitRole()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Role: admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
    }

    public function actionAssignAdmin($id)
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        $auth->assign($admin, $id);
    }

    public function actionAssignSale($id)
    {
        $auth = Yii::$app->authManager;
        $sale = $auth->getRole('sale');
        $auth->assign($sale, $id);
    }

    public function actionCreateRole()
    {
        $model = new CreateRoleForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }    
        }
        return $this->render('create-role.tpl', [
            'model' => $model,
        ]);
        
    }

    public function actionAssignRole()
    {
        $model = new AssignRoleForm();
        $model->scenario = AssignRoleForm::SCENARIO_ADD;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }    
        }

        $links = [
            'user_suggestion' => Url::to(['user/suggestion'])
        ];
        return $this->render('assign-role.tpl', [
            'model' => $model,
            'links' => $links
        ]);
        
    }

    public function actionChangeRole($userId)
    {
        $model = new AssignRoleForm(['user_id' => $userId]);
        $model->scenario = AssignRoleForm::SCENARIO_EDIT;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }    
        }

        return $this->render('change-role.tpl', [
            'model' => $model,
        ]);
        
    }
}