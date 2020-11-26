<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

use backend\forms\CreateCategoryForm;
use backend\forms\EditCategoryForm;

use backend\models\Category;

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
                        'roles' => ['system'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'category.index';
        $request = Yii::$app->request;
        $models = Category::find()->all();
        return $this->render('index.php', [
            'models' => $models,
        ]);
    }

    public function actionCreate($language)
    {
        $this->view->params['main_menu_active'] = 'category.index';
        $request = Yii::$app->request;
        $model = new CreateCategoryForm(['language' => $language]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['category/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'category.index';
        $request = Yii::$app->request;
        $model = new EditCategoryForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['category/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);

    }
}
