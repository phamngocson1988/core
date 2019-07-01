<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Game;
use frontend\components\cart\CartItem;

/**
 * GameController
 */
class GameController extends Controller
{
    public function actionView($id)
    {
    	// $model = CartItem::findOne($id);
     //    if (!$model) throw new BadRequestHttpException('Can not find the product');
    	$request = Yii::$app->request;
        $game = CartItem::findOne($id);
        if (!$game) throw new BadRequestHttpException('Can not find the product');
        $game->setScenario(CartItem::SCENARIO_ADD_CART);
        $game->load($request->post());
        $game->validate();
    	return $this->render('view', [
            'game' => $game
        ]);
    }
}