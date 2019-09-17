<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use backend\models\Game;
use backend\models\User;
use backend\models\Product;
use common\models\GameImage;
use backend\models\GameUnit;
use backend\models\GamePriceLog;
use backend\forms\FetchPriceLogForm;

class GameController extends Controller
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
                    // [
                    //     'allow' => true,
                    //     'actions' => ['suggestion'],
                    //     'roles' => ['@'],
                    // ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status');
        $command = Game::find();
        $command->where(['<>', 'status', Game::STATUS_DELETE]);
        if ($status) {
            $command->andWhere(['status' => $status]);
        }
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
        }
        $command->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new Game();
        $model->setScenario(Game::SCENARIO_CREATE);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = Url::to(['game/index']);
            return $this->redirect($ref);    
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['game/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model =Game::findOne($id);
        $model->setScenario(Game::SCENARIO_EDIT);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = Url::to(['game/index']);
            return $this->redirect($ref);    
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['game/index']))
        ]);
    }

    public function actionAddGallery($id)
    {
        $request = Yii::$app->request;
        $gallery = new GameImage();
        $gallery->game_id = $id;
        $gallery->image_id = $request->post('image_id');
        return $this->renderJson($gallery->save(), null, $gallery->getErrorSummary(true));
    }

    public function actionRemoveGallery($id)
    {
        $request = Yii::$app->request;
        $gallery = GameImage::findOne(['game_id' => $id, 'image_id' => $request->post('image_id')]);
        if ($gallery) {
            return $this->renderJson($gallery->delete(), null, $gallery->getErrorSummary(true));
        }
        return $this->renderJson(false, null, ['message' => 'not found']);
    }

    public function actionAddProduct($id)
    {
        $request = Yii::$app->request;
        $model = new Product();
        $model->setScenario(Product::SCENARIO_CREATE);
        $model->game_id = $id;
        if ($request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->renderJson(true, $model, []);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
                return $this->renderJson(false, null, $model->getErrorSummary(true));
            }
        }
    }

    public function actionProducts($id)
    {
        $request = Yii::$app->request;
        $model =Game::findOne($id);
        $products = $model->products;
        return $this->renderPartial('products.tpl', [
            'products' => $products,
        ]);
    }

    public function actionRemoveProduct($id)
    {
        $request = Yii::$app->request;
        $model = Product::findOne($id);
        return $this->renderJson($model->delete(), $model, $model->getErrorSummary(true));
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

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $command = Game::find();
                $command->where(['<>', 'status', Game::STATUS_DELETE]);
                if ($keyword) {
                    $command->andWhere(['like', 'title', $keyword]);
                }
                $games = $command->offset(0)->limit(20)->all();
                foreach ($games as $game) {
                    $item = [];
                    $item['id'] = $game->id;
                    $item['text'] = $game->title;
                    $items[] = $item;
                }
            }
            return $this->renderJson(true, ['items' => $items]);
        }
    }

    public function actionProvider()
    {
        $this->view->params['main_menu_active'] = 'game.provider';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status');
        $command = Game::find();
        $command->where(['<>', 'status', Game::STATUS_DELETE]);
        if ($status) {
            $command->andWhere(['status' => $status]);
        }
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
        }
        $command->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('provider.tpl', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionUpdatePrice($id)
    {
        $this->view->params['main_menu_active'] = 'game.provider';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = Game::findOne($id);
        $model->setScenario(Game::SCENARIO_CREATE);
        if ($request->isPost) {
            // Write log
            $model->on(Game::EVENT_AFTER_UPDATE, function($event) {
                $game = $event->sender; //game
                $oldGame = clone $game;
                $oldGame->attributes = $event->changedAttributes;
                $setting = Yii::$app->settings;
                $config = [
                    'managing_cost_rate' => $setting->get('ApplicationSettingForm', 'managing_cost_rate', 0),
                    'investing_cost_rate' => $setting->get('ApplicationSettingForm', 'investing_cost_rate', 0),
                    'desired_profit' => $setting->get('ApplicationSettingForm', 'desired_profit', 0),
                    'reseller_desired_profit' => $setting->get('ApplicationSettingForm', 'reseller_desired_profit', 0),
                ];
                $newPrice = $game->getPrice();
                $oldPrice = $oldGame->getPrice();
                $log = new GamePriceLog();
                $log->game_id = $game->id;
                $log->old_price = $oldPrice;
                $log->new_price = $newPrice;

                $log->old_reseller_1 = $oldGame->getResellerPrice(User::RESELLER_LEVEL_1);
                $log->new_reseller_1 = $game->getResellerPrice(User::RESELLER_LEVEL_1);

                $log->old_reseller_2 = $oldGame->getResellerPrice(User::RESELLER_LEVEL_2);
                $log->new_reseller_2 = $game->getResellerPrice(User::RESELLER_LEVEL_2);

                $log->old_reseller_3 = $oldGame->getResellerPrice(User::RESELLER_LEVEL_3);
                $log->new_reseller_3 = $game->getResellerPrice(User::RESELLER_LEVEL_3);
                $log->config = json_encode($event->changedAttributes);
                $log->save();
            });
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Cập nhật giá thành công cho game ' . $model->title);
                $ref = $request->get('ref', Url::to(['game/provider']));
                return $this->redirect($ref);    
            } else {
                $errors = $model->getErrorSummary(false);
                $message = reset($errors);
                Yii::$app->session->setFlash('error', $message);
            }
        }

        return $this->render('update-price.tpl', ['model' => $model]);
    }

    public function actionLog()
    {
        $this->view->params['main_menu_active'] = 'game.log';
        $request = Yii::$app->request;
        $form = new FetchPriceLogForm([
            'game_id' => $request->get('game_id'),
            'date_range' => $request->get('date_range'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('log', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }

    public function actionPrice()
    {
        $this->view->params['main_menu_active'] = 'game.price';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $command = Game::find();
        $command->where(['<>', 'status', Game::STATUS_DELETE]);
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('price', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }
}
