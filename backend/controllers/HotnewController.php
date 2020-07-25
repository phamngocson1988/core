<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\HotNew;

class HotnewController extends Controller
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
     * Show the list of posts
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'hotnew.index';
        $request = Yii::$app->request;
        $models = HotNew::find()->all();
        return $this->render('index.tpl', [
            'models' => $models,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'hotnew.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateHotNewForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                return $this->redirect(['hotnew/index']);
            }
        }

        return $this->render('create.tpl', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'hotnew.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditHotNewForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['post/index']));
                return $this->redirect(['hotnew/index']);
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit.tpl', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = HotNew::findOne($id);

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', 'Cannot delete this hot news');
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        return $this->redirect(['hotnew/index']);
    }
}
