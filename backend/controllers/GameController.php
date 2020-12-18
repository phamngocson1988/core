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
            'status' => $request->get('status'),
            'auto_dispatcher' => $request->get('auto_dispatcher'),
            'soldout' => $request->get('soldout')
        ]);
        $command = $form->getCommand();
        $command->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        // orders
        $gameIds = array_column($models, 'id');
        $orders = Order::find()
        ->where(['in', 'game_id', $gameIds])
        ->andWhere(['in', 'status', [Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED]])
        ->select(['game_id', 'COUNT(*) as total'])
        ->groupBy(['game_id'])
        ->asArray()->all();
        $orders = array_column($orders, 'total', 'game_id');

        // suppliers
        $suppliers = SupplierGame::find()
        ->where(['game_id' => $gameIds])
        ->andWhere(['status' => SupplierGame::STATUS_ENABLED])
        ->select(['game_id', 'COUNT(*) as total'])
        ->groupBy(['game_id'])
        ->asArray()->all();
        $suppliers = array_column($suppliers, 'total', 'game_id');

        // statictis
        $visibleCount = Game::find()->where(['status' => Game::STATUS_VISIBLE])->count();
        $invisibleCount = Game::find()->where(['status' => Game::STATUS_INVISIBLE])->count();
        $soldoutCount = Game::find()->where(['<>', 'status', Game::STATUS_DELETE])->andWhere(['soldout' => 1])->count();

        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'orders' => $orders,
            'suppliers'=> $suppliers,
            'ref' => Url::to($request->getUrl(), true),
            'visibleCount' => $visibleCount,
            'invisibleCount' => $invisibleCount,
            'soldoutCount' => $soldoutCount,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateGameForm();
        if ($model->load($request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = Url::to(['game/index']);
                return $this->redirect($ref);    
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
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
        $model = new \backend\forms\EditGameForm(['id' => $id]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = Url::to(['game/index']);
                return $this->redirect($ref);    
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        $model->loadData();
        $numSupplier = SupplierGame::find()->where(['game_id' => $id])->count();

        return $this->render('edit.php', [
            'id' => $id,
            'model' => $model,
            'back' => $request->get('ref', Url::to(['game/index'])),
            'numSupplier' => $numSupplier,
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
        // $model = Game::findOne($id);
        $model = new \backend\forms\UpdateGamePriceForm(['id' => $id]);
        $model->loadData();
        
        $form = new FetchSupplierGameForm([
            'game_id' => $id,
            'supplier_id' => $request->get('supplier_id'),
            'price_from' => $request->get('price_from'),
            'price_to' => $request->get('price_to'),
            'speed_from' => $request->get('speed_from'),
            'speed_to' => $request->get('speed_to'),
        ]);
        $command = $form->getCommand();
        $suppliers = $command->orderBy(['status' => SORT_DESC, 'auto_dispatcher' => SORT_DESC, 'price' => SORT_ASC])->all();
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
        $lastPrices = GamePriceLog::find()->where(['game_id' => $id])->orderBy(['updated_at' => SORT_DESC])->limit(2)->all();

        return $this->render('suppliers.php', [
            'model' => $model,
            'suppliers' => $suppliers,
            'id' => $id,
            'search' => $form,
            'countOrders' => $countOrders,
            'avgSpeeds' => $avgSpeeds,
            'lastPrices' => $lastPrices
        ]);
    }

    public function actionUpdatePrice($id)
    {
        $this->view->params['main_menu_active'] = 'game.provider';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new \backend\forms\UpdateGamePriceForm(['id' => $id]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->save()) {
                if ($request->isAjax) {
                    return $this->asJson(['status' => true]);
                } else {
                    $game = $model->getGame();
                    Yii::$app->session->setFlash('success', 'Cập nhật giá thành công cho game ' . $game->title);
                    $ref = $request->get('ref', Url::to(['game/provider']));
                    return $this->redirect($ref);    
                }
            } else {
                $errors = $model->getErrorSummary(false);
                $message = reset($errors);
                Yii::$app->session->setFlash('error', $message);
                if ($request->isAjax) {
                    return $this->asJson(['status' => false, 'errors' => $errors]);
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

    public function actionDispatcher()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $action = $request->get('action');
        $form = new \backend\forms\SwitchDispatcherForm([
            'id' => $id,
            'action' => $action,
        ]);
        if ($form->change()) {
            return $this->renderJson(true, ['next' => Url::to(['game/suppliers', 'id' => $id]), 'action' => $action]);
        } else {
            $errors = $form->getFirstErrors();
            return $this->renderJson(false, null, $errors);
        }
        return $this->redirectNotFound();
    }
}
