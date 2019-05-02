<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\FetchCustomerForm;
use backend\forms\ChangeCustomerStatusForm;
use backend\forms\CreateCustomerForm;
use backend\forms\EditCustomerForm;
use backend\forms\CreateCustomerProfileForm;
use backend\forms\EditCustomerProfileForm;
use backend\forms\TopupForm;
use backend\forms\FetchTransactionHistoryForm;
use common\models\CustomerDialer;
use common\models\Dialer;

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
                        'actions' => ['change-status', 'create', 'edit', 'create-profile', 'edit-profile', 'topup', 'history', 'create-dialer', 'edit-dialer'],
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

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new CreateCustomerForm();
        if ($model->load($request->post())) {
            if ($user = $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['customer/index']);
            }
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $model = EditCustomerForm::findOne($id);
        if ($model->load($request->post())) {
            if ($user = $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['customer/index']);
            }
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionCreateProfile($id)
    {
        $request = Yii::$app->request;
        $model = new CreateCustomerProfileForm(['customer_id' => $id]);
        if ($model->load($request->post())) {
            if ($user = $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['customer/index']);
            }
        }
        return $this->render('create-profile.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionEditProfile($id)
    {
        $request = Yii::$app->request;
        $model = EditCustomerProfileForm::findOne($id);
        if ($model->load($request->post())) {
            if ($user = $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['customer/index']);
            }
        }
        return $this->render('edit-profile.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionCreateDialer($id)
    {
        $request = Yii::$app->request;
        $type = $request->get('type');
        $model = new CustomerDialer();
        $model->setScenario($type);
        $model->customer_id = $id;
        $dialerModels = Dialer::findAll(['action' => $type]);
        $dialers = [];
        foreach ($dialerModels as $dialerModel) {
            $dialers[$dialerModel->id] = sprintf("%s - %s - %s", $dialerModel->number, $dialerModel->extend, $dialerModel->domain);
        }

        if ($model->load($request->post())) {
            if ($user = $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['customer/index']);
            }
        }

        return $this->render('create-dialer.tpl', [
            'model' => $model,
            'dialers' => $dialers,
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionEditDialer($id)
    {
        $request = Yii::$app->request;
        $model = CustomerDialer::findOne($id);
        $type = $model->dialer->action;
        $model->setScenario($type);
        $dialerModels = Dialer::findAll(['action' => $type]);
        $dialers = [];
        foreach ($dialerModels as $dialerModel) {
            $dialers[$dialerModel->id] = sprintf("%s - %s - %s", $dialerModel->number, $dialerModel->extend, $dialerModel->domain);
        }

        if ($model->load($request->post())) {
            if ($user = $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['customer/index']);
            }
        }

        return $this->render('edit-dialer.tpl', [
            'model' => $model,
            'dialers' => $dialers,
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionTopup($id)
    {
        $request = Yii::$app->request;
        $model = new TopupForm(['customer_id' => $id]);
        if ($model->load($request->post()) && $model->topup()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['customer/index']);
        }
        return $this->render('topup.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['customer/index']))
        ]);
    }

    public function actionHistory($id)
    {
        $this->view->params['main_menu_active'] = 'customer.index';
        $request = Yii::$app->request;
        $get = $request->get();
        $search = new FetchTransactionHistoryForm();
        $search->load($get, '');
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('history', [
            'models' => $models,
            'pages' => $pages,
            'search' => $search,
            'customer_id' => $id
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
}
