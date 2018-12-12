<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchProductForm;
use backend\forms\CreateProductForm;
use backend\forms\EditProductForm;
use backend\forms\DeleteProductForm;
use yii\helpers\Url;
use yii\data\Pagination;

class ProductController extends Controller
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
        $this->view->params['main_menu_active'] = 'product.index';
        $request = Yii::$app->request;

        $form = new FetchProductForm();
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
        $this->view->params['main_menu_active'] = 'product.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new CreateProductForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['product/index']));
                return $this->redirect($ref);    
            }
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['product/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'product.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new EditProductForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['product/index']));
                return $this->redirect($ref);    
            }
        } else {
            $model->loadData($id);
        }
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['product/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $form = new DeleteProductForm(['id' => $id]);
            return $this->renderJson($form->delete(), [], $form->getErrorSummary(true));
        }
        return $this->redirectNotFound();
    }
    
}
