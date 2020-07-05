<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;

class GameGroupController extends Controller
{
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
        $this->view->params['main_menu_active'] = 'game.group';
        $request = Yii::$app->request;
        
        $form = new \backend\forms\FetchGameGroupForm([
            'q' => $request->get('q'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionSetting()
    {
        $this->view->params['main_menu_active'] = 'game.setting';        
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateGameSettingForm();
        if ($request->isPost) {
            if ($model->load($request->post())) {
                if ($model->create()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                } else {
                    Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
                }
            }
        } else {
            $model->loadData();
        }

        return $this->render('setting', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'game.group';        
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateGameGroupForm();
        if ($request->isPost) {
            if ($model->load($request->post())) {
                if ($model->create()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                } else {
                    Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'game.group';        
        $request = Yii::$app->request;
        $model = new \backend\forms\EditGameGroupForm(['id' => $id]);
        if ($request->isPost) {
            if ($model->load($request->post())) {
                if ($model->create()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                } else {
                    Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
                }
            }
        } else {
        	$model->loadData();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }
}