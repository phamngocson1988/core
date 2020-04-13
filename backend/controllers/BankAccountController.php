<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use backend\models\BankAccountRole;

class BankAccountController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'bankaccount.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchBankAccountForm([
            'account_name' => $request->get('account_name'),
            'country' => $request->get('country'),
            'account_number' => $request->get('account_number'),
            'bank_id' => $request->get('bank_id'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        $ids = ArrayHelper::getColumn($models, 'id');
        // fetch role
        $auth = Yii::$app->authManager;
        $roleNames = ArrayHelper::map($auth->getRoles(), 'name', 'description');

        $roles = BankAccountRole::find()->where(['IN', 'bank_account_id', $ids])->select(['id', 'bank_account_id', 'role_id'])->asArray()->all();
        $roleByIds = ArrayHelper::map($roles, 'id', 'role_id', 'bank_account_id');
        $roleNameByIds = [];
        foreach ($roleByIds as $accId => $roles) {
            foreach ($roles as $roleName) {
                $roleNameByIds[$accId][] = ArrayHelper::getValue($roleNames, $roleName);
            }
        }
        // print_r($roleNameByIds);die;
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'roleNameByIds' => $roleNameByIds
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'bankaccount.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateBankAccountForm();
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa tạo mới ngân hàng thành công.');
                return $this->redirect(Url::to(['bank-account/index']));
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'bankaccount.index';
        $request = Yii::$app->request;
        $model = \backend\forms\EditBankAccountForm::findOne($id);
        if (!$model) throw new NotFoundHttpException("Không tìm thấy dữ liệu");
        
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa chỉnh sửa tài khoản ngân hàng thành công.');
                return $this->redirect(Url::to(['bank-account/index']));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        }
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionAssignRole($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\AssignBankAccountToRole([
            'id' => $id
        ]);

        if ($request->isPost && $request->isAjax) {
            if ($model->load($request->post()) && $model->validate() && $model->assign()) {
                return $this->asJson(['status' => true]);
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }

        }
        $model->loadCurrentRoles();
        return $this->renderPartial('assign-role', [
            'id' => $id,
            'model' => $model
        ]);    
        
    }

    public function actionReportBalance()
    {
        $request = Yii::$app->request;
        $currency = $request->get('currency');
        $this->view->params['main_menu_active'] = "bankaccount.{$currency}.reportbalance";
        $form = new \backend\forms\ReportBankAccountBalanceForm([
            'currency' => $currency,
        ]);
        if (!$form->validate()) {
            throw new NotFoundHttpException('Không tìm thấy trang');
        }
        $command = $form->getCommand();
        $models = $command->all();
        return $this->render('report-balance', [
            'models' => $models,
            'currency' => $currency,
        ]);
    }
}
