<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use backend\models\User;
use backend\forms\CreateRoleForm;
use backend\forms\AssignRoleForm;
use yii\helpers\Url;

class RbacController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create-role'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreateRole()
    {
        $this->view->params['main_menu_active'] = 'rbac.create-role';
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

}