<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

class GameProfitController extends Controller
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'game-profit.index';
        $request = Yii::$app->request;
        
        $form = new \backend\forms\FetchGameForm([
            'q' => $request->get('q'),
        ]);
        $command = $form->getCommand();
        $command->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'game-profit.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditGameProfitForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                return $this->redirect(Url::to(['game-profit/index']));
            }
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }
}
