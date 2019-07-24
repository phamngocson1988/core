<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\User;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Game;
use backend\models\GameReseller;

class ResellerController extends Controller
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
        $this->view->params['main_menu_active'] = 'game.reseller';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $command = User::find()->where([
            'is_reseller' => User::IS_RESELLER,
        ]);
        if ($q) {
            $command->andWhere(['like', 'email', $q]);
        }
        $command->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionPrice($id)
    {
        $this->view->params['main_menu_active'] = 'game.reseller';
        $request = Yii::$app->request;
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException('Not found');
        
        // Fetch all reseller prices
        $command = GameReseller::find()->where(['reseller_id' => $id])->with('game');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        // Fetch all games
        $games = Game::find()->select(['id', 'title', 'pack', 'unit_name'])->all();
        $games = array_map(function($game) {
            $game->title = sprintf("%s (%s %s)", $game->title, $game->pack, $game->unit_name);
            return $game;
        }, $games);
        $games = ArrayHelper::map($games, 'id', 'title');
        return $this->render('price', [
            'models' => $models,
            'pages' => $pages,
            'id' => $id,
            'games' => $games,
            'newModel' => new GameReseller()
        ]);
    }

    public function actionCreatePrice($id)
    {
        $request = Yii::$app->request;
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException('Not found');
        $model = new GameReseller();
        if ($model->load($request->post()) && $model->save()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $model->getErrorSummary(true)]);
        }
    }

    public function actionEditPrice($game_id, $reseller_id)
    {
        $request = Yii::$app->request;
        $model = GameReseller::findOne(['game_id' => $game_id, 'reseller_id' => $reseller_id]);
        if (!$model) throw new NotFoundHttpException('Not found price');
        if ($model->load($request->post()) && $model->save()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $model->getErrorSummary(true)]);
        }
    }
}