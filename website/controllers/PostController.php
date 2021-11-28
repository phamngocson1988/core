<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class PostController extends Controller
{
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'post.index';
        $form = new \website\forms\FetchPostForm();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['position' => SORT_DESC])
                            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionView($id)
    {
        $this->view->params['main_menu_active'] = 'post.index';
        $post = \common\models\Post::findOne($id);
        return $this->render('view', ['model' => $post]);
    }

    public function actionCategory($id) 
    {
        $this->view->params['main_menu_active'] = 'post.index';
        $form = new \website\forms\FetchPostForm(['category_id' => $id]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['position' => SORT_DESC])
                            ->all();
        $category = $form->getCategory();
        
        return $this->render('category', [
            'models' => $models,
            'pages' => $pages,
            'category' => $category
        ]);
    }
}