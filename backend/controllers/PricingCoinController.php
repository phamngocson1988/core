<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use backend\models\Package;
use yii\web\NotFoundHttpException;
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
                        'actions' => ['index', 'create', 'edit', 'set-best'],
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
        $models = Package::find()->all();
        return $this->render('index.tpl', [
            'models' => $models,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'coin.create';
        $request = Yii::$app->request;
        $model = new Package();
        $model->setScenario(Package::SCENARIO_CREATE);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = Url::to(['pricing-coin/index']);
            return $this->redirect($ref);    
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['coin/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'coin.index';
        $request = Yii::$app->request;
        $model = Package::findOne($id);
        if (!$model) throw new NotFoundHttpException('Not found');
        $model->setScenario(Package::SCENARIO_EDIT);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            $ref = $request->get('ref', Url::to(['pricing-coin/index']));
            return $this->redirect($ref);    
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['pricing-coin/index']))
        ]);
    }

    public function actionSetBest($id)
    {
        $model = Package::findOne($id);
        if (!$model) throw new NotFoundHttpException('Not found');
        Package::updateAll(['is_best' => Package::IS_NOT_BEST]);
        $model->is_best = self::IS_BEST;
        $model->save();
        return $this->redirect(Url::to(['pricing-coin/index'])); 
    }

}