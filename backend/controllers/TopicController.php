<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

class TopicController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['system_moderator'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'forum-topic.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchTopicForm([
            'q' => $request->get('q'),
            'category_id' => $request->get('category_id'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionList($id)
    {
        $this->view->params['main_menu_active'] = 'forum-topic.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchForumPostForm([
            'q' => $request->get('q'),
            'topic_id' => $id,
            'created_by' => $request->get('created_by'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $topic = \backend\models\ForumTopic::findOne($id);
        return $this->render('list', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'topic' => $topic,
        ]);
    }

    public function actionEditPost($id)
    {
        $this->view->params['main_menu_active'] = 'forum-topic.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditForumPostForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['topic/list', 'id' => $model->topic_id]);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData($id);
        }
        $topic = \backend\models\ForumTopic::findOne($model->topic_id);

        return $this->render('edit-post', [
            'model' => $model,
            'topic' => $topic,
        ]);

    }
}
