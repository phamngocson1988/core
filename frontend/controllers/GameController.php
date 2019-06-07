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
    	$model = Game::findOne($id);
        if (!$model) throw new BadRequestHttpException('Can not find the product');
        // $item = new CartItem(['game_id' => $id]);
        // $item->setScenario(CartItem::SCENARIO_ADD);
    	return $this->render('view', [
            'model' => $model,
            // 'item' => $item
        ]);
    }
}