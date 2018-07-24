<?php
namespace frontend\modules\shop\controllers;

use Yii;
use common\components\Controller;
use yii\helpers\Url;
use frontend\modules\shop\models\Category;

class CategoryController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $category = Category::findOne($id);
        echo '<pre>';
        echo $category->getReadUrl() . "<br>";
        print_r($category);

    } 

}
