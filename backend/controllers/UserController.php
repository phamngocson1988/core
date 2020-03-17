<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\CreateUserForm;
use backend\forms\EditUserForm;
use backend\forms\FetchUserForm;
use backend\forms\FetchLoginLogForm;
use backend\forms\ChangeUserStatusForm;

use backend\components\datepicker\DatePicker;
use common\models\UserRole;

/**
 * UserController
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'edit', 'change-status', 'login'],
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
        $this->view->params['main_menu_active'] = 'user.index';
        $request = Yii::$app->request;
        $form = new FetchUserForm();
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

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'user.index';
        $request = Yii::$app->request;
        $model = new CreateUserForm();
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        } 

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['user/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'user.index';        
        $request = Yii::$app->request;
        $model = new EditUserForm();
        $model->loadData($id);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->edit()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['user/index']))
        ]);
    }

    public function actionChangeStatus()
    {
        $request = Yii::$app->request;
        // if( $request->isAjax) {
            $id = $request->get('id');
            $status = $request->get('status');
            $form = new ChangeUserStatusForm(['id' => $id]);
            switch ($status) {
                case 'active':
                    $result = $form->active();
                    break;
                case 'delete':
                    $result = $form->delete();
                    break;                
                default:
                    $result = false;
                    break;
            }
            return $this->asJson(['status' => $result, 'errors' => 'Đã có lỗi xảy ra']);
        // }
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $form = new FetchUserForm(['q' => $keyword]);
                $command = $form->getCommand();
                $users = $command->offset(0)->limit(20)->all();
                foreach ($users as $user) {
                    $item = [];
                    $item['id'] = $user->id;
                    $item['text'] = sprintf("%s - %s", $user->username, $user->email);
                    $items[] = $item;
                }
            }
            return $this->asJson(['status' => true, 'data' => ['items' => $items]]);
        }
    }

    public function actionLogin()
    {
        $this->view->params['main_menu_active'] = 'user.login';
        $request = Yii::$app->request;
        $form = new FetchLoginLogForm([
            'user_id' => $request->get('user_id'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        // Find default roles 
        $auth = Yii::$app->authManager;
        $userIds = array_column($models, 'user_id');
        $userRoles = UserRole::find()->where(['in', 'user_id', $userIds])->all();
        $defaultRoles = [];
        foreach ($userRoles as $userRole) {
            $authRole = $auth->getRole($userRole->role);
            if (!$authRole) continue;
            $defaultRoles[$userRole->user_id][] = $authRole->description;
        }
        // print_r($defaultRoles);die;

        return $this->render('login', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'defaultRoles' => $defaultRoles
        ]);
    }
}
