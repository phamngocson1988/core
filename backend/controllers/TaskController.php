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
use backend\forms\UpdateTaskStatusForm;
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

    public function actionIndex()//app_todo_2.html
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

        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/apps/css/todo.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
        $this->view->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
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
        $model = new CreateTaskForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['task/index']));
                return $this->redirect($ref);
            }
        }
        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/apps/css/todo-2.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerJsFile('vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => '\backend\assets\AppAsset']);
        $this->view->registerJsFile('vendor/assets/apps/scripts/todo-2.min.js', ['depends' => '\backend\assets\AppAsset']);
        $links = [
            'user_suggestion' => Url::to(['user/suggestion'])
        ];
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['task/index'])),
            'links' => $links
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'task.index';
        $request = Yii::$app->request;
        $model = new EditTaskForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['task/index']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getFirstErrors());
            }
        } else {
            $model->loadData($id);
        }
        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/apps/css/todo-2.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerJsFile('vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => '\backend\assets\AppAsset']);
        $this->view->registerJsFile('vendor/assets/apps/scripts/todo-2.min.js', ['depends' => '\backend\assets\AppAsset']);
        $links = [
            'user_suggestion' => Url::to(['user/suggestion'])
        ];
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['task/index'])),
            'links' => $links
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

    public function actionUpdateStatus($id)
    {
        $request = Yii::$app->request;
        $form = new UpdateTaskStatusForm(['id' => $id]);
        $status = $request->get('status');
        $result = false;
        switch ($status) {
            case 'inprogress':
                $result = $form->inprogress();
                break;
            case 'done':
                $result = $form->done();
                break;
            case 'invalid':
                $result = $form->invalid();
                break;
            default:
                break;
        }
        if (!$result) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        if ($request->isAjax) {
            return $this->renderJson((boolean)$result, $form->getTask(), $form->getErrors('id'));
        }
        $ref = $request->get('ref', Url::to(['task/index']));
        return $this->redirect($ref);
    }
}
