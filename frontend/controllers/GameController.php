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
    	$request = Yii::$app->request;
        $game = CartItem::findOne($id);
        if (!$game) throw new BadRequestHttpException('Can not find the game');
        $game->setScenario(CartItem::SCENARIO_ADD_CART);
        if ($game->load($request->post()) && $game->validate()) {
            if ($request->isAjax) {
                return $this->asJson(['status' => true, 'data' => [
                    'price' => $game->getTotalPrice(),
                    'unit' => $game->getTotalUnit()
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
    	return $this->render('view', [
            'game' => $game,
        ]);
    }
}