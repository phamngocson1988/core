<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchProductForm;
use backend\forms\CreateProductForm;
use backend\forms\EditProductForm;
use backend\forms\ChangeProductStatusForm;
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
        $request = Yii::$app->request;
        $game_id = $request->get('game_id');
        $status = $request->get('status');
        $form = new FetchProductForm(['game_id' => $game_id, 'status' => $status]);
        if (!$form->validate()) {
            $models = [];  
        } else {
            $models = $form->fetch();
        }
        
        return $this->renderPartial('index.tpl', [
            'models' => $models,
            'game_id' => $game_id,
            'status' => $status,
            'form' => $form,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'product.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new CreateProductForm();
        if ($model->load(Yii::$app->request->post())) {
            $product = $model->save();
            if (!$product) {
                // Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
                return json_encode(['status' => false, 'errors' => $model->getErrors()]);
            } else {
                // Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return json_encode(['status' => true, 'data' => $product]);
            }
        } else {
            return $this->renderPartial('create.tpl', ['model' => $model]);
        }
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $model = new EditProductForm();
        if ($model->load(Yii::$app->request->post(), 'Product')) {
            if (!$model->save()) {
                return json_encode(['status' => false, 'errors' => $model->getErrors()]);
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $product = $model->getProduct();
                return json_encode(['status' => true, 'data' => $product]);
            }
        } else {
            // $model->loadData($id);
            $product = \common\models\Product::findOne($id);
            return $this->renderPartial('edit.tpl', ['product' => $product]);
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $form = new ChangeProductStatusForm(['id' => $id]);
            return $this->renderJson($form->delete(), [], $form->getErrorSummary(true));
        }
        return $this->redirectNotFound();
    }

    public function actionEnable($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $form = new ChangeProductStatusForm(['id' => $id]);
            return $this->renderJson($form->enable(), [], $form->getErrorSummary(true));
        }
        return $this->redirectNotFound();
    }

    public function actionDisable($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $form = new ChangeProductStatusForm(['id' => $id]);
            return $this->renderJson($form->disable(), [], $form->getErrorSummary(true));
        }
        return $this->redirectNotFound();
    }
}
