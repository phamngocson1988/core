<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

class CurrencyController extends Controller
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
        $this->view->params['main_menu_active'] = 'currency.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchCurrencyForm(['status' => $request->get('status')]);
        $models = $form->fetch();
        return $this->render('index', [
            'search' => $form,
            'models' => $models,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'currency.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateCurrencyForm();
        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = $request->get('ref', Url::to(['currency/index']));
            return $this->redirect(['currency/index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($code)
    {
        $this->view->params['main_menu_active'] = 'currency.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditCurrencyForm(['code' => $code]);
        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['currency/index']);
        } else {
            $model->loadData();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);

    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteCategoryForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['currency/index']));
        return $this->redirect($ref);
    }
}
