<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use website\models\Game;
use website\models\Promotion;
use website\components\cart\CartItem;

// form
use website\forms\FetchGameForm;

/**
 * GameController
 */
class GameController extends Controller
{
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $form = new FetchGameForm(['q' => $request->get('q')]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form
        ]);
    }
    public function actionView($id)
    {
    	$request = Yii::$app->request;
        $game = CartItem::findOne($id);
        if (!$game) throw new BadRequestHttpException('Can not find the game');
        $game->setScenario(CartItem::SCENARIO_ADD_CART);
        if ($game->load($request->post()) && $game->validate()) {
            if ($request->isAjax) {
                return $this->asJson(['status' => true, 'data' => [
                    'origin' => number_format($game->getTotalOriginalPrice(), 1),
                    'price' => number_format($game->getTotalPrice(), 1),
                    'unit' => number_format($game->getTotalUnit()),
                ]]);
            }
            $cart = Yii::$app->cart;
            $cart->clear();
            $cart->add($game);
            return $this->redirect(['cart/index']);
        }
        if ($request->isAjax) {
            return $this->asJson(['status' => false, 'game' => $game, 'error' => $game->getErrorSummary(true)]);
        }
        $promotions = Promotion::find()->andWhere(['rule_name' => 'specified_games'])->all();
        $is_reseller = false;
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $is_reseller = $user->isReseller();
        }

    	return $this->render('view', [
            'game' => $game,
            'promotions' => $promotions,
            'is_reseller' => $is_reseller
        ]);
    }

    public function actionHotDeal()
    {
        $form = new FetchGameForm(['hot_deal' => Game::HOT_DEAL]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionTopGrossing()
    {
        $form = new FetchGameForm(['top_grossing' => Game::TOP_GROSSING]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('top-grossing', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionNewTrending()
    {
        $form = new FetchGameForm(['new_trending' => Game::NEW_TRENDING]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('new-trending', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }
}