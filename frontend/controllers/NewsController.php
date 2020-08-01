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
use yii\data\Pagination;

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

    public function actionCategory($id, $slug)
    {
        $command = Post::find()->where(['category_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $posts = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $total = $command->count();
        return $this->render('category', [
            'posts' => $posts,
            'pages' => $pages,
            'total' => $total,
        ]);
    }

    public function actionOperator($id, $slug)
    {
        $command = Post::find()->where(['operator_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $posts = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $total = $command->count();
        return $this->render('operator', [
            'posts' => $posts,
            'pages' => $pages,
            'total' => $total,
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