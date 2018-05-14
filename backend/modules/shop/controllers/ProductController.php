<?php
namespace backend\modules\shop\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\modules\shop\forms\FetchProductForm;
use backend\modules\shop\forms\CreateProductForm;
use backend\modules\shop\forms\EditProductForm;
use backend\modules\shop\forms\DeleteProductForm;
use yii\data\Pagination;
use yii\helpers\Url;
use common\modules\shop\models\Product;

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
    
}
