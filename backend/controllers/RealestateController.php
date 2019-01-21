<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchRealestateForm;
use backend\forms\CreateRealestateForm;
use backend\forms\EditRealestateForm;
use backend\forms\DeleteRealestateForm;
use yii\helpers\Url;
use yii\data\Pagination;

class RealestateController extends Controller
{
    /**
     * @inheritdoc
     */
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
        $this->view->params['main_menu_active'] = 'realestate.index';
        $request = Yii::$app->request;

        $form = new FetchRealestateForm();
        if (!$form->validate()) {
            Yii::$app->session->setFlash('error', $form->getErrorSummary(true));
            return $this->redirect($ref);
        }
        $models = $form->fetch();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new CreateRealestateForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['realestate/index']));
                return $this->redirect($ref);    
            }
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['realestate/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new EditRealestateForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['realestate/index']));
                return $this->redirect($ref);    
            }
        } else {
            $model->loadData($id);
        }
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['realestate/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $form = new DeleteRealestateForm(['id' => $id]);
            return $this->renderJson($form->delete(), [], $form->getErrorSummary(true));
        }
        return $this->redirectNotFound();
    }
    
}
