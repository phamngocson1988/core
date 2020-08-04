<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;
use backend\models\GameGroup;
use backend\models\Game;

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

    // public function actionSetting()
    // {
    //     $this->view->params['main_menu_active'] = 'game.setting';        
    //     $request = Yii::$app->request;
    //     $model = new \backend\forms\CreateGameSettingForm();
    //     if ($request->isPost) {
    //         if ($model->load($request->post())) {
    //             if ($model->create()) {
    //                 Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
    //             } else {
    //                 Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
    //             }
    //         }
    //     } else {
    //         $model->loadData();
    //     }

    //     return $this->render('setting', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'game.group';        
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateGameGroupForm();
        if ($request->isPost) {
            if ($model->load($request->post())) {
                if ($group = $model->create()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                    return $this->redirect(['game-group/edit', 'id' => $group->id]);
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
                    return $this->redirect(['game-group/index']);
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

    public function actionDelete($id)
    {
        $group = GameGroup::findOne($id);
        if ($group) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $group->delete();
                $games = Game::find()->where(['group_id' => $id])->all();
                foreach ($games as $game) {
                    $game->group_id = null;
                    $game->method = null;
                    $game->version = null;
                    $game->package = null;
                    $game->save();
                }
                $transaction->commit();
            } catch(Exception $e) {
                $transaction->rollback();
                return false;
            }
        }
        $name = $group ? $group->title : '';
        return $this->asJson(['status' => true, 'data' => ['message' => sprintf("Bạn đã xoá nhóm game %s thành công", $name)]]);
    }
}