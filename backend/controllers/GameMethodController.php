<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use backend\models\GameMethod;

class GameMethodController extends Controller
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
        $this->view->params['main_menu_active'] = 'gamemethod.index';
        $request = Yii::$app->request;
        $models = GameMethod::find()->all();

        return $this->render('index', [
            'models' => $models,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'gamemethod.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateGameMethodForm();
        if ($model->load($request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = Url::to(['game-method/index']);
                return $this->redirect($ref);    
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
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditGameMethodForm(['id' => $id]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = Url::to(['game-method/index']);
                return $this->redirect($ref);    
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        $model->loadData();

        return $this->render('edit.php', [
            'model' => $model,
        ]);
    }
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $game = Game::findOne($id);
            return $this->renderJson($game->delete());
        }
        return $this->redirectNotFound();
    }
}
