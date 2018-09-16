<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\FetchStaffForm;
use backend\forms\CreateStaffForm;
use backend\forms\EditStaffForm;
use backend\forms\ChangeStaffStatusForm;

/**
 * StaffController
 */
class StaffController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'edit'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'index1', 'suggestion'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'staff.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $branch = $request->get('branch');
        $department = $request->get('department');
        $gender = $request->get('gender', '');
        $form = new FetchStaffForm([
            'q' => $q, 
            'gender' => $gender,
            'branch' => $branch,
            'department' => $department,
        ]);

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'staff.index';
        $request = Yii::$app->request;
        $model = new EditStaffForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['staff/index']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData($id);
        }
        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/apps/css/todo-2.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerJsFile('vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => '\backend\assets\AppAsset']);
        $this->view->registerJsFile('vendor/assets/apps/scripts/todo-2.min.js', ['depends' => '\backend\assets\AppAsset']);
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['staff/index']))
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'staff.index';
        $request = Yii::$app->request;
        $model = new CreateStaffForm();
        if ($model->load($request->post())) {
            if ($user = $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['staff/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/apps/css/todo-2.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerJsFile('vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => '\backend\assets\AppAsset']);
        $this->view->registerJsFile('vendor/assets/apps/scripts/todo-2.min.js', ['depends' => '\backend\assets\AppAsset']);
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['staff/index']))
        ]);
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $form = new FetchStaffForm(['q' => $keyword]);
                $command = $form->getCommand();
                $staffs = $command->offset(0)->limit(20)->all();
                foreach ($staffs as $staff) {
                    $item = [];
                    $item['id'] = $staff->id;
                    $item['text'] = sprintf("%s - %s", $staff->username, $staff->email);
                    $items[] = $item;
                }
            }
            return $this->renderJson(true, ['items' => $items]);
        }
    }
}
