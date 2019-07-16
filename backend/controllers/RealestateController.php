<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\forms\FetchRealestateForm;
use backend\forms\CreateRealestateForm;
use backend\forms\EditRealestateForm;
use backend\forms\DeleteRealestateForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
        $q = $request->get('q');
        $command = Realestate::find();
        if ($q) {
            $command->orWhere(['like', 'title', $q]);
            $command->orWhere(['like', 'excerpt', $q]);
            $command->orWhere(['like', 'content', $q]);
            $command->orWhere(['like', 'address', $q]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
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
            foreach ($model->realestate_services as $serviceData) {
                $realestateService = new RealestateService($serviceData);
                $realestateService->realestate_id = $model->id;
                if ($realestateService->validate()) $realestateService->save();
                else Yii::$app->session->setFlash('warning', "Một số dịch vụ bị lỗi, chưa được lưu vào trong nhà cho thuê này");
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = $request->get('ref', Url::to(['realestate/index']));
            return $this->redirect($ref);    
        }
        $services = ArrayHelper::map(Service::find()->all(), 'id', 'title');
        return $this->render('create.php', [
            // 'map' => $map,
            'model' => $model,
            'services' => $services,
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
            $newServiceIds = array_filter(ArrayHelper::getColumn($model->realestate_services, 'id'));
            $oldServiceIds = ArrayHelper::getColumn($model->realestateServices, 'id');
            $deletedServiceIds = array_diff($oldServiceIds, $newServiceIds);
            foreach ($deletedServiceIds as $deletedServiceId) {
                $realestateService = RealestateService::findOne($deletedServiceId);
                $realestateService->delete();
            }
            foreach ($model->realestate_services as $serviceData) {
                $realestateServiceId = ArrayHelper::getValue($serviceData, 'id');
                if ($realestateServiceId) {
                    $realestateService = RealestateService::findOne($realestateServiceId);
                    $realestateService->price = ArrayHelper::getValue($serviceData, 'price', 0);
                } else {
                    $realestateService = new RealestateService($serviceData);
                    $realestateService->realestate_id = $model->id;
                }
                if ($realestateService->validate()) $realestateService->save();
                else Yii::$app->session->setFlash('warning', "Một số dịch vụ bị lỗi, chưa được lưu vào trong nhà cho thuê này");
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = $request->get('ref', Url::to(['realestate/index']));
            return $this->redirect($ref);    
        }
        $services = ArrayHelper::map(Service::find()->all(), 'id', 'title');
        $model->realestate_services = $model->realestateServices;
        return $this->render('edit.php', [
            'model' => $model,
            'services' => $services,
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
}
