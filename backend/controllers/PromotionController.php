<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\models\Promotion;
use backend\models\PromotionSearch;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use backend\models\Game;
use common\models\User;
use backend\models\PromotionGame;
use backend\models\PromotionUser;

class PromotionController extends Controller
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

    /**
     * Lists all Promotion models.
     * @return mixed
     */
    public function actionIndex($promotion_scenario)
    {
        $menu = (Promotion::SCENARIO_BUY_COIN == $promotion_scenario) ? 'package.promotion' : 'game.promotion';
        $this->view->params['main_menu_active'] = $menu;
        $request = Yii::$app->request;
        $command = Promotion::find();
        $command->where(['promotion_scenario' => $promotion_scenario]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();


        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'promotion_scenario' => $promotion_scenario,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate($promotion_scenario)
    {
        $menu = (Promotion::SCENARIO_BUY_COIN == $promotion_scenario) ? 'package.promotion' : 'game.promotion';
        $this->view->params['main_menu_active'] = $menu;
        $request = Yii::$app->request;
        $model = new Promotion(['scenario' => Promotion::SCENARIO_CREATE]);
        $model->promotion_scenario = $promotion_scenario;
        if ($model->load($request->post())) {
            if ($model->rule_name) {
                $rule = Promotion::pickRule($model->rule_name);
                $rule->load($request->post());
                $model->setRuleData($rule);
            }
            if ($model->benefit_name) {
                $benefit = Promotion::pickBenefit($model->benefit_name);
                $benefit->load($request->post());
                $model->setBenefitData($benefit);
            }
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['promotion/index', 'promotion_scenario' => $promotion_scenario]);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('create', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['promotion/index']))
        ]);
    }

    public function actionEdit($id)
    {
        
        $request = Yii::$app->request;
        $model = Promotion::findOne($id);
        $model->setScenario(Promotion::SCENARIO_EDIT);

        if ($model->load($request->post())) {
            if ($model->rule_name) {
                $rule = Promotion::pickRule($model->rule_name);
                $rule->load($request->post());
                $model->setRuleData($rule);
            }
            if ($model->benefit_name) {
                $benefit = Promotion::pickBenefit($model->benefit_name);
                $benefit->load($request->post());
                $model->setBenefitData($benefit);
            }
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['promotion/index', 'promotion_scenario' => $model->promotion_scenario]);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        $menu = (Promotion::SCENARIO_BUY_COIN == $model->promotion_scenario) ? 'package.promotion' : 'game.promotion';
        $this->view->params['main_menu_active'] = $menu;
        return $this->render('edit', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['promotion/index']))
        ]);
    }
}
