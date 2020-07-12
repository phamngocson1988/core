<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\models\ForumCategory;
use frontend\models\ForumSection;
use frontend\models\ForumPost;
use frontend\models\ForumTopic;

class ForumController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $sections = ForumSection::find()->all();
        return $this->render('index', [
            'sections' => $sections
        ]);
    }

    public function actionCategory($id, $slug)
    {
        $category = ForumCategory::findOne($id);
        $command = ForumTopic::find()->where(['category_id' => $id])->with('creator');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $topics = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $total = $command->count();

        return $this->render('category', [
            'category' => $category,
            'topics' => $topics,
            'pages' => $pages,
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new \frontend\forms\CreateTopicForumForm([
        ]);
        if ($model->load($request->post())) {
            if ($model->validate() && $newId = $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['forum/topic', 'id' => $newId]);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionTopic($id, $slug)
    {
        $topic = ForumTopic::findOne($id);
        $command = ForumPost::find()->where(['topic_id' => $id])->with('sender');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $posts = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $total = $command->count();

        $model = new \frontend\forms\ReplyForumForm(['topic_id' => $id]);

        return $this->render('topic', [
            'topic' => $topic,
            'posts' => $posts,
            'pages' => $pages,
            'model' => $model,
        ]);
    }

    public function actionReply($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ReplyForumForm([
            'topic_id' => $id
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->reply()) {
            return json_encode([
                'status' => true, 
                'data' => [
                    'message' => Yii::t('app', 'add_reply_success'),
                    'id' => $id
                ]
            ]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}