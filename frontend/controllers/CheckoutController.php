<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CheckoutController
 */
class CheckoutController extends Controller
{
    public function actionIndex()
    {
        $cart = Yii::$app->cart;
        $items = $cart->getItems();
        return $this->render('index', ['items' => $items]);
    }
}