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
use website\models\User;

class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'blockip' => [
                'class' => \website\components\filters\BlockIpAccessControl::className(),
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['like', 'rating', 'comment'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'view', 'category', 'comments', 'replies'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'like' => ['post'],
                    'rating' => ['post'],
                    'comment' => ['post'],
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
        $total = \common\models\PostRating::find()->where(['post_id' => $id])->count();
        $average = \common\models\PostRating::find()->where(['post_id' => $id])->average('rating');
        return $this->asJson(['status' => true, 'average' => number_format($average, 1), 'total' => number_format($total)]);
    }

    public function actionComments()
    {
        $request = Yii::$app->request;
        $sort = $request->get('sort', 'desc');
        $lastKey = $request->get('lastKey', 0);
        $post_id = $request->get('post_id');
        $form = new \website\forms\FetchPostCommentForm([
            'post_id' => $post_id,
            'sort' => $sort,
            'lastKey' => $lastKey
        ]);
        $total = $form->getTotal();
        $comments = $form->fetch();

        if ($comments === false) {
            return $this->asJson(['status' => false]);
        }
        $userIds = ArrayHelper::getColumn($comments, 'created_by');
        $userIds = array_unique($userIds);
        $users = User::findAll($userIds);
        $userNameMapping = ArrayHelper::map($users, 'id', function($u) {
            return $u->getName();
        });
        $comments = array_map(function($r) use ($userNameMapping) {
            $replyContent = $r->attributes;
            $replyContent['creator'] = $userNameMapping[$r->created_by];
            return $replyContent;
        }, $comments);

        return $this->asJson(['status' => true, 'comments' => $comments, 'total' => $total]);
    }

    public function actionComment($id)
    {
        $request = Yii::$app->request;
        $content = $request->post('content');
        $parentId = $request->post('parent_id');
        $form = new \website\forms\CreatePostCommentForm([
            'post_id' => $id, 
            'content' => $content,
            'user_id' => Yii::$app->user->id,
            'parent_id' => $parentId
        ]);
        $newComment = $form->save();
        return $this->asJson(['status' => !!$newComment, 'comment' => $newComment]);
    }

    public function actionReplies($id)
    {
        $request = Yii::$app->request;
        $form = new \website\forms\FetchCommentReplyForm([
            'id' => $id, 
        ]);
        $replies = $form->fetch();
        if ($replies === false) {
            return $this->asJson(['status' => false]);
        }
        $userIds = ArrayHelper::getColumn($replies, 'created_by');
        $userIds = array_unique($userIds);
        $users = User::findAll($userIds);
        $userNameMapping = ArrayHelper::map($users, 'id', function($u) {
            return $u->getName();
        });
        $replies = array_map(function($r) use ($userNameMapping) {
            $replyContent = $r->attributes;
            $replyContent['creator'] = $userNameMapping[$r->created_by];
            return $replyContent;
        }, $replies);
        return $this->asJson(['status' => true, 'comments' => $replies]);
    }
}