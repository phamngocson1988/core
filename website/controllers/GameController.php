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

// form
use website\forms\FetchGameForm;
use website\components\cart\CartItem;

/**
 * GameController
 */
class GameController extends Controller
{
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $form = new FetchGameForm([
            'q' => $request->get('q'),
            'category_id' => $request->get('category_id'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }
    public function actionView($id)
    {
    	$request = Yii::$app->request;
        $game = Game::findOne($id);
        $model = new CartItem(['game_id' => $id]);
    	return $this->render('view', [
            'game' => $game,
            'model' => $model
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