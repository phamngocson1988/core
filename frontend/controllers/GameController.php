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
    	if (!$model) throw new BadRequestHttpException('Không tìm thấy sản phẩm');
        $item = new CartItem([
            'scenario' => CartItem::SCENARIO_ADD_ITEM,
        ]);
    	return $this->render('view', [
            'model' => $model,
            'item' => $item
        ]);
    }
}