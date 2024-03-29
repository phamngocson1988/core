<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\SignupForm;
use backend\forms\CreateUserForm;
use backend\forms\EditUserForm;
use backend\forms\FetchUserForm;
use backend\forms\ChangeUserStatusForm;
use backend\forms\InviteUserForm;
use backend\forms\FetchCustomerForm;
use backend\forms\FetchCustomerNotOrderForm;
use backend\models\User;
use backend\behaviors\UserResellerBehavior;

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
                        'actions' => ['index', 'create', 'edit', 'invite', 'change-status', 'active', 'inactive', 'update-trust', 'update-not-trust', 'no-order', 'assign-saler'],
                        'roles' => ['admin', 'hr', 'sale_manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['suggestion', 'index'],
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
        $mode = $request->get('mode');
        $data = [
            'user_id' => $request->get('user_id'),
            'created_start' => $request->get('created_start'),
            'created_end' => $request->get('created_end'),
            'birthday_start' => $request->get('birthday_start'),
            'birthday_end' => $request->get('birthday_end'),
            'country_code' => $request->get('country_code'),
            'game_id' => $request->get('game_id'),
            'purchase_start' => $request->get('purchase_start'),
            'purchase_end' => $request->get('purchase_end'),
            'saler_id' => $request->get('saler_id'),
            'is_reseller' =>  User::IS_NOT_RESELLER,
            'total_purchase_start' => $request->get('total_purchase_start'),
            'total_purchase_end' => $request->get('total_purchase_end'),
            'last_purchase_start' => $request->get('last_purchase_start'),
            'last_purchase_end' => $request->get('last_purchase_end'),
            'total_topup_start' => $request->get('total_topup_start'),
            'total_topup_end' => $request->get('total_topup_end'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'is_supplier' => User::IS_NOT_SUPPLIER
        ];
        $form = new FetchCustomerForm($data);
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'danh-sach-khach-hang.xls';
            return $form->export($fileName);
        }
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $command->orderBy(['id' => SORT_DESC]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        $links = [
            'delete' => Url::to(['user/change-status', 'status' => 'delete']),
            'active' => Url::to(['user/change-status', 'status' => 'active'])
        ];
        $createResellerService = new \backend\forms\CreateResellerForm();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'links' => $links,
            'createResellerService' => $createResellerService
        ]);
    }

    public function actionNoOrder()
    {
        $this->view->params['main_menu_active'] = 'user.no-order';
        $request = Yii::$app->request;
        $mode = $request->get('mode');
        $data = [
            'user_id' => $request->get('user_id'),
            'created_start' => $request->get('created_start'),
            'created_end' => $request->get('created_end'),
            // 'country_code' => $request->get('country_code'),
            'no_purchase_start' => $request->get('no_purchase_start'),
            'no_purchase_end' => $request->get('no_purchase_end'),
            'saler_id' => $request->get('saler_id'),
            // 'is_reseller' => $request->get('is_reseller'),
        ];
        $form = new FetchCustomerNotOrderForm($data);
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'danh-sach-khach-hang.xls';
            return $form->export($fileName);
        }
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $command->orderBy(['id' => SORT_DESC]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        $links = [
            'delete' => Url::to(['user/change-status', 'status' => 'delete']),
            'active' => Url::to(['user/change-status', 'status' => 'active'])
        ];

        return $this->render('no-order', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'links' => $links
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'user.index';        
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $model = new CreateUserForm();
        if ($model->load($request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
            'back' => $request->get('ref', Url::to(['user/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'user.index';        
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $model = new EditUserForm();
        $model->id = $id;
        if ($model->load($request->post())) {
            if ($user = $model->edit()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData();
        }

        return $this->render('edit', [
            'model' => $model,
            'roles' => $roles,
            'back' => $request->get('ref', Url::to(['user/index']))
        ]);
    }

    public function actionInvite()
    {
        $this->view->params['main_menu_active'] = 'user.index';        
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $model = new InviteUserForm();
        if ($model->load($request->post())) {
            if ($user = $model->invite()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        return $this->render('invite.tpl', [
            'model' => $model,
            'roles' => $roles,
            'back' => $request->get('ref', Url::to(['user/index']))
        ]);
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
                    $item['text'] = sprintf("%s - %s", $user->name, $user->email);
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
            return $this->renderJson($result, null, $form->getErrors());
        }
    }

    public function actionActive($id) 
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $user->status = User::STATUS_ACTIVE;
            return $this->asJson(['status' => $user->save(true, ['status'])]);
        }
    }

    public function actionInactive($id) 
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $user->status = User::STATUS_INACTIVE;
            return $this->asJson(['status' => $user->save(true, ['status'])]);
        }
    }

    public function actionUpdateTrust($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $user->trust = User::IS_TRUST;
            return $this->asJson(['status' => $user->save(true, ['trust'])]);
        }
    }

    public function actionUpdateNotTrust($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $user->trust = User::IS_NOT_TRUST;
            return $this->asJson(['status' => $user->save(true, ['trust'])]);
        }
    }

    public function actionAssignSaler($id)
    {
        $request = Yii::$app->request;
        $salerId = $request->post('saler_id');
        $form = new \backend\forms\AssignSalerToUserForm([
            'user_id' => $id,
            'saler_id' => $salerId,
            'force_update' => Yii::$app->user->can('admin')
        ]);
        if (!$form->run()) {
            $errors = $form->getFirstErrors();
            $error = reset($errors);
            Yii::$app->session->setFlash('error', $error);
            return $this->asJson(['status' => false, 'errors' => $error]);
        } else {
            return $this->asJson(['status' => true]);
        }
    }
}
