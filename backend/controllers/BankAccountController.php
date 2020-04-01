<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

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
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
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
}
