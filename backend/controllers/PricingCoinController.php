<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchImageForm;
use yii\helpers\Url;
use backend\forms\CreatePricingCoinForm;
use backend\forms\EditPricingCoinForm;
use common\models\PricingCoin;
use yii\web\NotFoundHttpException;
use backend\forms\SetPricingCoinBest;
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
        $models = PricingCoin::find()->all();
        return $this->render('index.tpl', [
            'models' => $models,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'coin.create';
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
        $request = Yii::$app->request;
        $model = EditPricingCoinForm::findOne($id);
        if (!$model) throw new NotFoundHttpException('Not found');
        
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['pricing-coin/index']));
                return $this->redirect($ref);    
            }
        }
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['pricing-coin/index']))
        ]);
    }

    public function actionSetBest($id)
    {
        $request = Yii::$app->request;
        $model = SetPricingCoinBest::findOne($id);
        $result = $model->setBest();
        return $this->redirect(Url::to(['pricing-coin/index'])); 
    }

}