<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

use supplier\models\Game;
use supplier\models\SupplierGame;
use supplier\forms\FetchGameForm;

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
        ]);
        $command = $form->getCommand();
        $command->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
        ]);
    }

    public function actionMyGame()
    {
        $this->view->params['main_menu_active'] = 'game.my-game';
        $form = new \supplier\forms\FetchMyGameForm(['supplier_id' => Yii::$app->user->id]);

        $command = $form->getCommand();
        $command->with('game');
        $command->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('my-game', [
            'models' => $models,
            'pages' => $pages,
        ]); 
    }

    public function actionAdd($id) 
    {
        try {
            $game = Game::findOne($id);
            if (!$game) throw new Exception("Game không tồn tại", 1);
            $model = new SupplierGame([
                'supplier_id' => Yii::$app->user->id,
                'game_id' => $id,
                'status' => SupplierGame::STATUS_DISABLED
            ]);
            $model->setScenario(SupplierGame::SCENARIO_CREATE);
            return $this->asJson(['status' => $model->save(), 'errors' => 'Error']);
        } catch (\Exception $e) {
            return $this->asJson(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function actionRemove($id) 
    {
        try {
            $model = SupplierGame::findOne([
                'supplier_id' => Yii::$app->user->id,
                'game_id' => $id
            ]);
            if (!$model) throw new Exception("Supplier chưa đăng ký game này", 1);
            return $this->asJson(['status' => $model->delete(), 'errors' => 'Error']);
        } catch (\Exception $e) {
            return $this->asJson(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function actionEnable($id) 
    {
        try {
            $model = SupplierGame::findOne([
                'supplier_id' => Yii::$app->user->id,
                'game_id' => $id
            ]);
            if (!$model) throw new Exception("Supplier chưa đăng ký game này", 1);
            $model->setScenario(SupplierGame::SCENARIO_STATUS);
            $model->status = SupplierGame::STATUS_ENABLED;
            return $this->asJson(['status' => $model->save(), 'errors' => 'Error']);
        } catch (\Exception $e) {
            return $this->asJson(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function actionDisable($id) 
    {
        try {
            $model = SupplierGame::findOne([
                'supplier_id' => Yii::$app->user->id,
                'game_id' => $id
            ]);
            if (!$model) throw new Exception("Supplier chưa đăng ký game này", 1);
            $model->setScenario(SupplierGame::SCENARIO_STATUS);
            $model->status = SupplierGame::STATUS_DISABLED;
            $model->auto_dispatcher = SupplierGame::AUTO_DISPATCHER_OFF;
            return $this->asJson(['status' => $model->save(), 'errors' => 'Error']);
        } catch (\Exception $e) {
            return $this->asJson(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function actionPrice($id) 
    {
        $request = Yii::$app->request;
        $model = new \supplier\forms\EditGamePriceForm([
            'supplier_id' => Yii::$app->user->id,
            'game_id' => $id
        ]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->update()) {
                return $this->asJson(['status' => true]);
            } else {
                $error = $model->getFirstErrorMessage();
                return $this->asJson(['status' => false, 'error' => $error]);
            }
        }
        return $this->renderPartial('_price', ['model' => $model]);
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
