<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use backend\models\Game;
use backend\models\User;
use backend\models\Product;
use backend\models\Order;
use common\models\GameImage;
use backend\models\GameUnit;
use backend\models\GamePriceLog;
use backend\forms\FetchPriceLogForm;
use backend\models\SupplierGame;
use backend\forms\FetchGameForm;
use backend\forms\FetchSupplierGameForm;

use backend\models\OrderSupplier;

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
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        
        $form = new FetchGameForm([
            'q' => $request->get('q'),
            'status' => $request->get('status')
        ]);
        $command = $form->getCommand();
        $command->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        $gameIds = array_column($models, 'id');
        $orders = Order::find()
        ->where(['in', 'game_id', $gameIds])
        ->andWhere(['status' => Order::STATUS_CONFIRMED])
        ->select(['game_id', 'COUNT(*) as total'])
        ->groupBy(['game_id'])
        ->asArray()->all();
        $orders = array_column($orders, 'total', 'game_id');

        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'orders' => $orders,
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
        $numSupplier = SupplierGame::find()->where(['game_id' => $id])->count();
        return $this->render('edit.php', [
            'id' => $id,
            'model' => $model,
            'back' => $request->get('ref', Url::to(['game/index'])),
            'numSupplier' => $numSupplier
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

    public function actionSuppliers($id)
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        if (!Yii::$app->user->can('orderteam')) throw new NotFoundHttpException('Not found');
        $request = Yii::$app->request;
        $model = Game::findOne($id);

        $form = new FetchSupplierGameForm([
            'game_id' => $id,
            'supplier_id' => $request->get('supplier_id'),
            'price_from' => $request->get('price_from'),
            'price_to' => $request->get('price_to'),
            'speed_from' => $request->get('speed_from'),
            'speed_to' => $request->get('speed_to'),
        ]);
        $command = $form->getCommand();
        $suppliers = $command->all();
        $supplierIds = array_column($suppliers, 'supplier_id');
        $speedCommand = OrderSupplier::find()
        ->where(['game_id' => $id])
        ->andWhere(['in', 'supplier_id', $supplierIds])
        ->andWhere(['in', 'status', [OrderSupplier::STATUS_COMPLETED, OrderSupplier::STATUS_CONFIRMED]])
        ->groupBy(['supplier_id'])
        ->select(['supplier_id', 'COUNT(*) as count_order', 'AVG(TIMESTAMPDIFF(SECOND, processing_at, completed_at)) as duration'])
        ->asArray();
        if ($form->speed_from) {
            $speedCommand->andHaving(['>=', 'duration', $form->speed_from * 60]);
        }
        if ($form->speed_to) {
            $speedCommand->andHaving(['<=', 'duration', $form->speed_to * 60]);
        }
        // echo $speedCommand->createCommand()->getRawSql(); die;
        $orderSuppliers = $speedCommand->all();
        if ($form->speed_from || $form->speed_to) {
            $filterSupplierIds = array_column($orderSuppliers, 'supplier_id');
            $suppliers = array_filter($suppliers, function($s) use ($filterSupplierIds) {
                return in_array($s->supplier_id, $filterSupplierIds);
            });
        }
        $countOrders = array_column($orderSuppliers, 'count_order', 'supplier_id');
        $avgSpeeds = array_column($orderSuppliers, 'duration', 'supplier_id');
        $lastPrice = $model->lastChange;

        return $this->render('suppliers.php', [
            'model' => $model,
            'suppliers' => $suppliers,
            'id' => $id,
            'search' => $form,
            'countOrders' => $countOrders,
            'avgSpeeds' => $avgSpeeds,
            'lastPrice' => $lastPrice
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
                $attrs = [
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice,
                    'old_price_1' => $oldGame->price1,
                    'old_price_2' => $oldGame->price2,
                    'old_price_3' => $oldGame->price3,
                    'new_price_1' => $game->price1,
                    'new_price_2' => $game->price2,
                    'new_price_3' => $game->price3,
                    'old_reseller_1' => $oldGame->getResellerPrice(User::RESELLER_LEVEL_1),
                    'new_reseller_1' => $game->getResellerPrice(User::RESELLER_LEVEL_1),
                    'old_reseller_2' => $oldGame->getResellerPrice(User::RESELLER_LEVEL_2),
                    'new_reseller_2' => $game->getResellerPrice(User::RESELLER_LEVEL_2),
                    'old_reseller_3' => $oldGame->getResellerPrice(User::RESELLER_LEVEL_3),
                    'new_reseller_3' => $game->getResellerPrice(User::RESELLER_LEVEL_3),
                ];
                Yii::error($attrs, 'actionUpdatePrice attrs');
                $log = new GamePriceLog();
                foreach ($attrs as $key => $value) {
                    $log->$key = $value;
                }
                $log->game_id = $game->id;
                $log->config = json_encode(array_merge($event->changedAttributes, $config));
                $log->save();

                // Send mail to saler
                $salerIds = Yii::$app->authManager->getUserIdsByRole('saler');
                $saleManagerIds = Yii::$app->authManager->getUserIdsByRole('sale_manager');
                $salerTeamIds = array_merge($salerIds, $saleManagerIds);
                $salerTeamIds = array_unique($salerTeamIds);
                $salerTeams = User::findAll($salerTeamIds);
                $salerEmails = ArrayHelper::getColumn($salerTeams, 'email');
                $admin = Yii::$app->params['email_admin'];
                $siteName = Yii::$app->name;
                Yii::$app->mailer->compose('notify_updating_game_price', [
                    'game' => $game,
                    'changes' => $attrs
                ])
                ->setTo($salerEmails)
                ->setFrom([$admin => $siteName])
                ->setSubject(sprintf("KINGGEMS.US - Game %s have just been updated its price", $game->title))
                ->setTextBody(sprintf("KINGGEMS.US - Game %s have just been updated its price", $game->title))
                ->send();
            });
            if ($model->load($request->post()) && $model->save()) {
                if ($request->isAjax) {
                    return $this->asJson(['status' => true]);
                } else {
                    Yii::$app->session->setFlash('success', 'Cập nhật giá thành công cho game ' . $model->title);
                    $ref = $request->get('ref', Url::to(['game/provider']));
                    return $this->redirect($ref);    
                }
            } else {
                $errors = $model->getErrorSummary(false);
                $message = reset($errors);
                Yii::$app->session->setFlash('error', $message);
                if ($request->isAjax) {
                    return $this->asJson(['status' => false, 'errors' => $message]);
                }
            }
        }

        // Load suppliers
        $suppliers = SupplierGame::find()
        ->where(['game_id' => $id])
        ->orderBy(['price' => SORT_ASC])
        ->with('user')->all();

        return $this->render('update-price.tpl', [
            'model' => $model,
            'suppliers' => $suppliers,
            'id' => $id,
        ]);
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
