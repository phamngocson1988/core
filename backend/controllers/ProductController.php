<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchProductForm;
use backend\forms\CreateProductForm;
use backend\forms\EditProductForm;
use backend\forms\DeleteProductForm;
use yii\data\Pagination;
use backend\forms\FetchCategoryForm;
use backend\forms\CreateCategoryForm;
use backend\forms\EditCategoryForm;
use backend\forms\DeleteCategoryForm;
use yii\helpers\Url;
use common\models\Product;

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

    /**
     * Show the list of products
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'product.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status');
        $form = new FetchProductForm(['q' => $q, 'status' => $status]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

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
        $request = Yii::$app->request;
        $model = new CreateProductForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['product/index']));
                return $this->redirect($ref);
            }
        }

        $this->view->registerJsFile('@web/js/ckeditor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerCssFile('@web/vendor/assets/global/plugins/fancybox/source/jquery.fancybox.css', ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
        $this->view->registerJsFile('@web/vendor/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['product/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'product.index';
        $request = Yii::$app->request;
        $model = new EditProductForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['product/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }
        $this->view->registerJsFile('@web/js/ckeditor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerCssFile('@web/vendor/assets/global/plugins/fancybox/source/jquery.fancybox.css', ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
        $this->view->registerJsFile('@web/vendor/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['product/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteProductForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', 'Success!');
        $ref = $request->get('ref', Url::to(['product/index']));
        return $this->redirect($ref);
    }

    public function actionCategory()
    {
        $this->view->params['main_menu_active'] = 'product.category';
        $request = Yii::$app->request;
        $form = new FetchCategoryForm(['type' => 'product']);
        $models = $form->fetch();
        return $this->render('category.tpl', [
            'models' => $models,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreateCategory()
    {
        $this->view->params['main_menu_active'] = 'product.category';
        $request = Yii::$app->request;
        $model = new CreateCategoryForm(['type' => 'product']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['product/category']));
                return $this->redirect($ref);
            }
        }
        return $this->render('create-category.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['product/category']))
        ]);
    }

    public function actionEditCategory($id)
    {
        $this->view->params['main_menu_active'] = 'product.category';
        $request = Yii::$app->request;
        $model = new EditCategoryForm(['type' => 'product']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['product/category']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit-category.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['product/category']))
        ]);

    }

    public function actionDeleteCategory($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteCategoryForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', 'Success!');
        $ref = $request->get('ref', Url::to(['product/category']));
        return $this->redirect($ref);
    }
    
}
