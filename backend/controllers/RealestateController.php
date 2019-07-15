<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\forms\FetchRealestateForm;
use backend\forms\CreateRealestateForm;
use backend\forms\EditRealestateForm;
use backend\forms\DeleteRealestateForm;
use yii\helpers\Url;
use yii\data\Pagination;
use common\models\Realestate;
use common\models\Service;
use common\models\RealestateService;

class RealestateController extends Controller
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
        $this->view->params['main_menu_active'] = 'realestate.index';
        $request = Yii::$app->request;

        $form = new FetchRealestateForm();
        if (!$form->validate()) {
            Yii::$app->session->setFlash('error', $form->getErrorSummary(true));
            return $this->redirect($ref);
        }
        $models = $form->fetch();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        // $model = new CreateRealestateForm();
        $model = new Realestate();
        if ($model->load(Yii::$app->request->post())) {
            // if (!$model->save()) {
            //     Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            // } else {
                // Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                // $ref = $request->get('ref', Url::to(['realestate/index']));
                // return $this->redirect($ref);    
            // }

            if ($model->electric_name) {
                $electric = Realestate::pickElectric($model->electric_name);
                $electric->load($request->post());
                $model->setElectricData($electric);
            }
            if ($model->water_name) {
                $water = Realestate::pickWater($model->water_name);
                $water->load($request->post());
                $model->setWaterData($water);
            }
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = $request->get('ref', Url::to(['realestate/index']));
            return $this->redirect($ref);    
        }

        return $this->render('create.php', [
            // 'map' => $map,
            'model' => $model,
            'back' => $request->get('ref', Url::to(['realestate/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $model = Realestate::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            // if (!$model->save()) {
            //     Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            // } else {
            //     Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            //     $ref = $request->get('ref', Url::to(['realestate/index']));
            //     return $this->redirect($ref);    
            // }
            if ($model->electric_name) {
                $electric = Realestate::pickElectric($model->electric_name);
                $electric->load($request->post());
                $model->setElectricData($electric);
            }
            if ($model->water_name) {
                $water = Realestate::pickWater($model->water_name);
                $water->load($request->post());
                $model->setWaterData($water);
            }
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = $request->get('ref', Url::to(['realestate/index']));
            return $this->redirect($ref);    
        }
        return $this->render('edit.php', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['realestate/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $form = new DeleteRealestateForm(['id' => $id]);
            return $this->renderJson($form->delete(), [], $form->getErrorSummary(true));
        }
        return $this->redirectNotFound();
    }

    public function actionService($id)
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $request = Yii::$app->request;
        $realestate = Realestate::findOne($id);
        $services = Service::find()->all();
        $model = new RealestateService();
        $model->realestate_id = $id;
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Thành công');
            $model = new RealestateService();
            $request->bodyParams = [];
        }
        return $this->render('service', [
            'realestate' => $realestate,
            'model' => $model,
            'services' => $services
        ]);
    }

    public function actionDeleteService($id)
    {
        $request = Yii::$app->request;
        $model = RealestateService::findOne($id);
        if ($model && $model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionEditService($id)
    {
        $request = Yii::$app->request;
        $model = RealestateService::findOne($id);
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }
}
