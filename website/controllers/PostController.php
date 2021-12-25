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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['like', 'rating'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'view', 'category'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'like' => ['post'],
                    'rating' => ['post'],
                ],
            ]
        ];
    }
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
        $post->updateCounters(['view_count' => 1]);
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

    public function actionLike($id) 
    {
        $userId = Yii::$app->user->id;
        $likeData = ['post_id' => $id, 'user_id' => $userId];
        $like = \common\models\PostLike::find()->where($likeData)->one();
        if ($like) {
            $like->delete();
            return $this->asJson(['status' => true, 'is_like' => false]);
        } else {
            $like = new \common\models\PostLike($likeData);
            $like->save();
            return $this->asJson(['status' => true, 'is_like' => true]);
        }
    }

    public function actionRating($id) 
    {
        $request = Yii::$app->request;
        $userId = Yii::$app->user->id;
        $likeData = ['post_id' => $id, 'user_id' => $userId];
        $like = \common\models\PostRating::find()->where($likeData)->one();
        if (!$like) {
            $like = new \common\models\PostRating($likeData);
        }
        $like->rating = $request->post('rating', 1);
        $like->save();
        return $this->asJson(['status' => true, 'like' => $like]);
    }

    public function actionComments($id)
    {
        $form = new \website\forms\FetchPostCommentForm(['post_id' => $id]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
    }
}