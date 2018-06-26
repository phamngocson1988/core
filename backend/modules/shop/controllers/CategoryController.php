<?php
namespace backend\modules\shop\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use backend\forms\FetchCategoryForm;
use backend\forms\CreateCategoryForm;
use backend\forms\EditCategoryForm;
use backend\forms\DeleteCategoryForm;
use yii\helpers\Url;

class CategoryController extends Controller
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
        $this->view->params['main_menu_active'] = 'product.category.index';
        $request = Yii::$app->request;
        $form = new FetchCategoryForm(['type' => 'product']);
        $models = $form->fetch();
        return $this->render('index.tpl', [
            'models' => $models,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'product.category.index';
        $request = Yii::$app->request;
        $model = new CreateCategoryForm(['type' => 'product']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['category/index']));
                return $this->redirect($ref);
            }
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['category/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'product.category.index';
        $request = Yii::$app->request;
        $model = new EditCategoryForm(['type' => 'product']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['category/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['category/index']))
        ]);

    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteCategoryForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', 'Success!');
        $ref = $request->get('ref', Url::to(['category/index']));
        return $this->redirect($ref);
    }
    
}
