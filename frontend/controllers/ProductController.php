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
 * ProductController
 */
class ProductController extends Controller
{
    public $layout = 'product';
    public function actionIndex()
    {
        $form = new FetchProductForm();
        $products = $form->fetch();
        return $this->render('index', ['products' => $products]);
    }

    public function actionView($id)
    {return $this->render('view');
    	// $product = Product::findOne($id);
    	// if (!$product) throw new BadRequestHttpException('Không tìm thấy sản phẩm');
    	// return $this->render('view', ['product' => $product]);
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