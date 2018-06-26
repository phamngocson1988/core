<?php
namespace frontend\modules\shop\controllers;

use Yii;
use common\components\Controller;
use yii\helpers\Url;
use frontend\modules\shop\models\Product;

class ProductController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $product = Product::findOne($id);
        echo '<pre>';
        print_r($product);

    } 
}
