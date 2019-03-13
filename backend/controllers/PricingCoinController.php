<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchImageForm;
use yii\helpers\Url;
use backend\forms\CreatePricingCoinForm;

/**
 * PricingCoinController
 */
class PricingCoinController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'edit', 'delete'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

	public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'coin.index';
        $request = Yii::$app->request;
        return $this->render('index.tpl');
        // $form = new FetchCoinForm();
        // if (!$form->validate()) {
        //     Yii::$app->session->setFlash('error', $form->getErrorSummary(true));
        //     return $this->redirect($ref);
        // }
        // $models = $form->fetch();
        // $command = $form->getCommand();
        // $pages = new Pagination(['totalCount' => $command->count()]);
        // return $this->render('index.tpl', [
        //     'models' => $models,
        //     'pages' => $pages,
        //     'form' => $form,
        //     'ref' => Url::to($request->getUrl(), true),
        // ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'coin.index';
        $request = Yii::$app->request;
        $model = new CreatePricingCoinForm();
        if ($model->load(Yii::$app->request->post())) {
            $coin = $model->save();
            if (!$coin) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = Url::to(['pricing-coin/index']);
                return $this->redirect($ref);    
            }
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['coin/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'coin.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = new EditCoinForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['coin/index']));
                return $this->redirect($ref);    
            }
        } else {
            $model->loadData($id);
        }
        $newProductModel = new CreateProductForm(['coin_id' => $id]);
        $editProductModel = new EditProductForm(['coin_id' => $id]);
        return $this->render('edit.tpl', [
            'model' => $model,
            'newProductModel' => $newProductModel,
            'editProductModel' => $editProductModel,
            'back' => $request->get('ref', Url::to(['coin/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $coin = Coin::findOne($id);
            return $this->renderJson($coin->delete());
        }
        return $this->redirectNotFound();
    }

}