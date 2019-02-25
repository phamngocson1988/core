<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\forms\FetchProductForm;
use frontend\models\AddCartForm;
use frontend\models\Product;
use frontend\components\cart\CartItem;

/**
 * CartController
 */
class CartController extends Controller
{
    public function actionIndex()
    {
        $cart = Yii::$app->cart;
        $items = $cart->getItems();
        return $this->render('index', ['items' => $items]);
    }

    public function actionAdd()
    {
    	$request = Yii::$app->request;
    	if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
    	if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

    	// Add to cart
        $id = $request->post('id');
        $quantity = $request->post('quantity', 1);
        $item = new CartItem(['id' => $id, 'quantity' => $quantity]);
        $item->setScenario(CartItem::SCENARIO_ADD_ITEM);
    	if (!$item->validate()) {
    		die('invalid');
    	}

    	Yii::$app->cart->add($item);    	
        return $this->redirect('index');
        die;
    }

    public function actionQuantity()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        // $id = $request->post('id');
        // $quantity = $request->post('quantity');
        $item = new CartItem();
        $item->setScenario(CartItem::SCENARIO_ADD_ITEM);
        if ($item->load($request->post) && $item->validate()) {
            $cart = Yii::$app->cart;
            $cart->addItem($item);
            return json_encode(['status' => true]);die;
        }


    }
}