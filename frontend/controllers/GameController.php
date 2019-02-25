<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\Game;

/**
 * GameController
 */
class GameController extends Controller
{
    public function actionIndex()
    {
        $form = new FetchGameForm();
        $games = $form->fetch();
        return $this->render('index', ['games' => $games]);
    }

    public function actionView($id)
    {
    	$model = Game::findOne($id);
    	if (!$model) throw new BadRequestHttpException('Không tìm thấy sản phẩm');
        $item;
    	return $this->render('view', ['model' => $model]);
    }

    public function actionAddCart()
    {
    	$request = Yii::$app->request;
    	if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
    	if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

    	// Add to cart
    	$id = $request->post('id');
    	$item = new CartItem(['id' => $id]);
    	if (!$item->validate()) {
    		die('invalid');
    	}

    	Yii::$app->cart->add($item);    	
    }

    public function actionCart()
    {
    	// get all items from the cart
    	$cart = Yii::$app->cart;
		$items = $cart->getItems();

		return $this->render('cart', ['items' => $items]);
    }

    public function actionAdd($id)
    {
    	// Add to cart
    	$item = new CartItem(['id' => $id]);
    	if (!$item->validate()) {
    		print_r($item->getErrors());die;
    	}
    	Yii::$app->cart->add($item);    		
    	die('success');
    }
}