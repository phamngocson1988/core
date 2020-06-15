<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\models\Post;
use frontend\models\Category;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $operatorNews = Post::find()->where(['IS NOT', 'operator_id', null])->limit(5)->all();
        $categoryModels = Category::find()->select(['id'])->limit(2)->all();
        $categoryIds = ArrayHelper::getColumn($categoryModels, 'id');
        return $this->render('index', [
            'operatorNews' => $operatorNews,
            'categoryIds' => $categoryIds
        ]);
    }

    public function actionView($id, $slug)
    {
        $request = Yii::$app->request;

        $post = Post::findOne($id);
        return $this->render('view', [
            'post' => $post
        ]);
    }
}