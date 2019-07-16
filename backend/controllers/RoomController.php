<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use backend\models\Room;
use backend\models\RoomService;
use backend\models\Service;
use backend\models\Realestate;

/**
 * RoomController implements the CRUD actions for Room model.
 */
class RoomController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Room models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $realestate = Realestate::findOne($id);
        if (!$realestate) throw new NotFoundHttpException('Không tồn tại');
        $rooms = $realestate->rooms;

        // room services
        $realestateServiceCommand = $realestate->getRealestateServices();
        $realestateServices = $realestateServiceCommand->indexBy('id')->with('service')->all();
        $services = array_map(function ($item) {
            return $item->service;
        }, $realestateServices);

        return $this->render('index', [
            'realestate' => $realestate,
            'rooms' => $rooms,
            'services' => $services
        ]);
    }

    public function actionSearch()
    {
        $this->view->params['main_menu_active'] = 'realestate.room';
        $command = Room::find()->with('realestate');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $rooms = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        $services = Service::find()->indexBy('id')->all();
        return $this->render('search', [
            'rooms' => $rooms,
            'services' => $services
        ]);
    }


    /**
     * Creates a new Room model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $realestate = Realestate::findOne($id);
        $model = new Room();
        $model->setScenario(Room::SCENARIO_CREATE);
        $model->realestate_id = $id;
        if ($model->load($request->post()) && $model->save()) {
            // print_r($request->post('roomServices', []));die;
            foreach ($request->post('roomServices', []) as $rsId => $service) {
                $roomService = new RoomService();
                $roomService->realestate_service_id = $rsId;
                $roomService->room_id = $model->id;
                $roomService->price = $service['price'];
                $roomService->apply = (int)ArrayHelper::getValue($service, 'apply');
                $roomService->save();
            }
            return $this->redirect(['room/index', 'id' => $id]);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        // room services
        $realestateServiceCommand = $realestate->getRealestateServices();
        $realestateServices = $realestateServiceCommand->indexBy('id')->with('service')->all();
        $roomServices = array_map(function($item) {
            $rs = new RoomService();
            $rs->realestate_service_id = $item->id;
            $rs->price = $item->price;
            $rs->apply = 0;
            return $rs;
        }, $realestateServices);
        $services = array_map(function ($item) {
            return $item->service;
        }, $realestateServices);

        return $this->render('create', [
            'realestate' => $realestate,
            'model' => $model,
            'roomServices' => $roomServices,
            'services' => $services
        ]);
    }

    public function actionEdit($id, $roomId)
    {
        $this->view->params['main_menu_active'] = 'realestate.index';
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $request = Yii::$app->request;
        $realestate = Realestate::findOne($id);
        $model = Room::findOne($roomId);
        $model->setScenario(Room::SCENARIO_EDIT);
        if ($model->load($request->post()) && $model->save()) {
            foreach ($request->post('roomServices', []) as $rsId => $service) {
                $roomService = RoomService::findOne(['room_id' => $roomId, 'realestate_service_id' => $rsId]);
                if (!$roomService) {
                    $roomService = new RoomService();
                    $roomService->room_id = $roomId;
                    $roomService->realestate_service_id = $rsId;
                }
                $roomService->price = $service['price'];
                $roomService->apply = (int)ArrayHelper::getValue($service, 'apply');
                $roomService->save();
            }
            return $this->redirect(['room/index', 'id' => $id]);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        // room services
        $realestateServiceCommand = $realestate->getRealestateServices();
        $realestateServices = $realestateServiceCommand->indexBy('id')->with('service')->all();
        $roomServiceCommand = $model->getRoomServices();
        $roomServices = $roomServiceCommand->indexBy('realestate_service_id')->all();
        $missingServiceIds = array_diff(array_keys($realestateServices), array_keys($roomServices));
        if (!empty($missingServiceIds)) {
            foreach ($realestateServices as $realestateService) {
                if (in_array($realestateService->id, $missingServiceIds)) {
                    $rs = new RoomService();
                    $rs->room_id = $roomId;
                    $rs->realestate_service_id = $realestateService->id;
                    $rs->price = $realestateService->price;
                    $rs->apply = 0;
                    $roomServices[$realestateService->id] = $rs;
                }
            }
        }
        $services = array_map(function ($item) {
            return $item->service;
        }, $realestateServices);

        return $this->render('edit', [
            'realestate' => $realestate,
            'model' => $model,
            'roomServices' => $roomServices,
            'services' => $services
        ]);
    }
}
