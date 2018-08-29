<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;

use backend\forms\FetchTaskForm;
use backend\forms\CreateTaskForm;
use backend\forms\EditTaskForm;
use backend\forms\DeleteTaskForm;
use yii\helpers\Url;

class TaskController extends Controller
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'task.index';
        $request = Yii::$app->request;
        
        $condition = [];
        $condition['status'] = $request->get('status');

        if (Yii::$app->user->can('admin')) {
            $condition['created_by'] = $request->get('created_by');
            $condition['assignee'] = $request->get('assignee');
        } else {
            $condition['assignee'] = Yii::$app->user->id;
            $condition['created_by'] = Yii::$app->user->id;            
        }

        $form = new FetchTaskForm($condition);
        $command = $form->getCommand();

        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        $links = [
            'user_suggestion' => Url::to(['user/suggestion'])
        ];
        if (Yii::$app->user->can('admin')) {
            $creator = $form->getCreator();
            $assignee = $form->getAssignee();
        } else {
            $creator = $assignee = Yii::$app->user->getIdentity();
        }
        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'links' => $links,
            'creator' => $creator,
            'assignee' => $assignee
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'task.index';
        $request = Yii::$app->request;
        $model = new CreateTaskForm(['type' => 'post']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['task/index']));
                return $this->redirect($ref);
            }
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['task/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'task.index';
        $request = Yii::$app->request;
        $model = new EditTaskForm(['type' => 'post']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['task/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['task/index']))
        ]);

    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteTaskForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['task/index']));
        return $this->redirect($ref);
    }
}
