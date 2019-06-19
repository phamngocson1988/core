<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\models\Promotion;
use backend\models\PromotionSearch;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use backend\models\Game;
use common\models\User;
use backend\models\PromotionGame;
use backend\models\PromotionUser;

class PromotionController extends Controller
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

    /**
     * Lists all Promotion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'promotion.index';
        $request = Yii::$app->request;
        $command = Promotion::find();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();


        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'promotion.index';
        $request = Yii::$app->request;
        $model = new Promotion(['scenario' => Promotion::SCENARIO_CREATE]);
        $model->on(Promotion::EVENT_AFTER_INSERT, function ($event) {
            $promotion = $event->sender;
            foreach ($promotion->game_ids as $gameId) {
                $game = new PromotionGame();
                $game->promotion_id = $promotion->id;
                $game->game_id = $gameId;
                $game->from_date = $promotion->from_date;
                $game->to_date = $promotion->to_date;
                $game->save();
            }
            foreach ($promotion->user_ids as $userId) {
                $user = new PromotionUser();
                $user->promotion_id = $promotion->id;
                $user->user_id = $userId;
                $user->from_date = $promotion->from_date;
                $user->to_date = $promotion->to_date;
                $user->save();
            }
        });
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['promotion/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        $games = Game::find()->select(['id', 'title'])->where(['IN', 'status', [Game::STATUS_VISIBLE, Game::STATUS_INVISIBLE]])->all();
        $users = User::find()->select(['id', 'email'])->where(['IN', 'status', [User::STATUS_ACTIVE, User::STATUS_INACTIVE]])->all();
        return $this->render('create', [
            'model' => $model,
            'games' => $games,
            'users' => $users,
            'back' => $request->get('ref', Url::to(['promotion/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'promotion.index';
        $request = Yii::$app->request;
        $model = Promotion::findOne($id);
        $model->setScenario(Promotion::SCENARIO_EDIT);
        $model->on(Promotion::EVENT_AFTER_UPDATE, function ($event) {
            $promotion = $event->sender;
            foreach ($promotion->promotionGames as $game) {
                $game->delete();
            }

            foreach ($promotion->promotionUsers as $user) {
                $user->delete();
            }
            foreach ($promotion->game_ids as $gameId) {
                $game = new PromotionGame();
                $game->promotion_id = $promotion->id;
                $game->game_id = $gameId;
                $game->from_date = $promotion->from_date;
                $game->to_date = $promotion->to_date;
                $game->save();
            }

            foreach ($promotion->user_ids as $userId) {
                $user = new PromotionUser();
                $user->promotion_id = $promotion->id;
                $user->user_id = $userId;
                $user->from_date = $promotion->from_date;
                $user->to_date = $promotion->to_date;
                $user->save();
            }
        });
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['promotion/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        $games = Game::find()->select(['id', 'title'])->where(['IN', 'status', [Game::STATUS_VISIBLE, Game::STATUS_INVISIBLE]])->all();
        $users = User::find()->select(['id', 'email'])->where(['IN', 'status', [User::STATUS_ACTIVE, User::STATUS_INACTIVE]])->all();
        $model->game_ids = ArrayHelper::getColumn($model->promotionGames, 'game_id');
        $model->user_ids = ArrayHelper::getColumn($model->promotionUsers, 'user_id');
        return $this->render('edit', [
            'model' => $model,
            'games' => $games,
            'users' => $users,
            'back' => $request->get('ref', Url::to(['promotion/index']))
        ]);
    }
}
