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
use supplier\behaviors\UserSupplierBehavior;

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
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
        ]);
    }

    public function actionMyGame()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $supplier = Yii::$app->user->getIdentity();
        $supplier->attachBehavior('supplier', new UserSupplierBehavior);
        $command = $supplier->getSupplierGames();
        $command->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
        ]); 
    }

    public function actionAdd($id) 
    {
        try {
            $game = Game::findOne($id);
            if (!$game) throw new Exception("Game không tồn tại", 1);
            $model = new SupplierGame([
                'supplier_id' => Yii::$app->user->id,
                'game_id' => $id
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
}
