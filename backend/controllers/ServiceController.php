<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\Service;
use yii\web\NotFoundHttpException;

class ServiceController extends Controller
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
        $this->view->params['main_menu_active'] = 'service.index';
        $request = Yii::$app->request;
        $models = Service::find()->all();
        return $this->render('index', [
            'models' => $models,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'service.index';
        $request = Yii::$app->request;
        $model = new Service();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['service/index']));
                return $this->redirect($ref);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['service/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'service.index';
        $request = Yii::$app->request;
        $model = Service::findOne($id);
        if (!$model) throw new NotFoundHttpException('Không tồn tại');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['service/index']));
                return $this->redirect($ref);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['service/index']))
        ]);

    }
}
