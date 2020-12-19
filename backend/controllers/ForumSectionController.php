<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\ForumSection;

class ForumSectionController extends Controller
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
        $this->view->params['main_menu_active'] = 'forum-section.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchForumSectionForm([
            'q' => $request->get('q'),
            'language' => $request->get('language'),
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

    public function actionCreate($language)
    {
        $this->view->params['main_menu_active'] = 'forum-section.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateForumSectionForm(['language' => $language]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['forum-section/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'forum-section.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditForumSectionForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['forum-section/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);

    }
}
