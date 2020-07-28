<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\FlashSale;
use backend\models\FlashSaleGame;

class FlashsaleController extends Controller
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
        $this->view->params['main_menu_active'] = 'flashsale.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchFlashSaleForm();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'flashsale.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditFlashSaleForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['flashsale/index']));
                return $this->redirect($ref);
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

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'flashsale.index';
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $model = new \backend\forms\CreateFlashSaleForm();
        if ($model->load($request->post())) {
            if ($flashsaleId = $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['flashsale/edit', 'id' => $flashsaleId]);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionAddGame($id) 
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\AddGameToFlashSaleForm();
        if ($request->isPost) {
            $model->load($request->post());
            $model->flashsale_id = $id;
            if ($model->validate() && $model->add()) {
                return $this->asJson(['status' => true]);
            }
            $errors = $model->getErrorSummary(true);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'error' => $error]);
        }
        return $this->renderPartial('add-game', [
            'id' => $id,
            'model' => $model,
        ]);
    }

    public function actionEditGame($id) 
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\EditGameToFlashSaleForm(['id' => $id]);
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate() && $model->edit()) {
                return $this->asJson(['status' => true]);
            }
            $errors = $model->getErrorSummary(true);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'error' => $error]);
        } else {
            $model->loadData();
        }
        return $this->renderPartial('edit-game', [
            'id' => $id,
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $flashsale = FlashSale::findOne($id);
        if ($flashsale) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $flashsale->delete();
                $games = FlashSaleGame::find()->where(['flashsale_id' => $id])->all();
                foreach ($games as $game) {
                    $game->delete();
                }
                $transaction->commit();
            } catch(Exception $e) {
                $transaction->rollback();
                return false;
            }
        }
        $name = $flashsale ? $flashsale->title : '';
        return $this->asJson(['status' => true, 'data' => ['message' => sprintf("Bạn đã xoá flash sale %s thành công", $name)]]);
    }
}
