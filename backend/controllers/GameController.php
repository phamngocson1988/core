<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use backend\models\Game;
use backend\models\Product;
use common\models\GameImage;

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
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['suggestion'],
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
        return $this->render('index.tpl', [
            'models' => $command->all(),
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
        $product = new Product();
        $product->game_id = $id;
        return $this->render('edit.tpl', [
            'model' => $model,
            'newProductModel' => $product,
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
}
