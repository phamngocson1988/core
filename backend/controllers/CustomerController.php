<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\FetchCustomerForm;
use backend\forms\CreateCustomerForm;
use backend\forms\EditCustomerForm;
use backend\forms\ChangeCustomerStatusForm;

/**
 * CustomerController
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['change-status', 'create', 'edit'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'suggestion'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'customer.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status', '');
        $form = new FetchCustomerForm(['q' => $q, 'status' => $status]);

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $links = [
            'delete' => Url::to(['customer/change-status', 'status' => 'delete']),
            'active' => Url::to(['customer/change-status', 'status' => 'active'])
        ];

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'links' => $links
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'customer.index';
        $request = Yii::$app->request;
        $model = new EditCustomerForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['customer/index']));
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
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'customer.index';
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $model = new CreateCustomerForm();
        if ($model->load($request->post())) {
            if ($user = $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['customer/index']);
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
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $form = new FetchCustomerForm(['q' => $keyword]);
                $command = $form->getCommand();
                $customers = $command->offset(0)->limit(20)->all();
                foreach ($customers as $customer) {
                    $item = [];
                    $item['id'] = $customer->id;
                    $item['text'] = sprintf("%s - %s", $customer->username, $customer->email);
                    $items[] = $item;
                }
            }
            return $this->renderJson(true, ['items' => $items]);
        }
    }

    public function actionChangeStatus()
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $id = $request->get('id');
            $status = $request->get('status');
            $form = new ChangeCustomerStatusForm(['id' => $id]);
            switch ($status) {
                case 'active':
                    $result = $form->active();
                    break;
                case 'delete':
                    $result = $form->delete();
                    break;                
                case 'inactive':
                    $result = $form->inactive();
                    break;                
                default:
                    $result = false;
                    break;
            }
            return $this->renderJson($result, null, $form->getErrors());
        }
    }
}
