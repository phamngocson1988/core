<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use common\models\Dialer;
/**
 * DialerController
 */
class DialerController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'dialer.index';
        $request = Yii::$app->request;
        $command = Dialer::find();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'dialer.index';
        $request = Yii::$app->request;
        $model = new Dialer();
        $model->setScenario(Dialer::SCENARIO_CREATE);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['dialer/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['dialer/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'dialer.index';
        $request = Yii::$app->request;
        $model = Dialer::findOne($id);
        $model->setScenario(Dialer::SCENARIO_EDIT);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['dialer/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['dialer/index']))
        ]);
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $command = Dialer::find();
                $command->orWhere(['like', 'domain', $keyword]);
                $command->orWhere(['like', 'number', $keyword]);
                $command->orWhere(['like', 'extend', $keyword]);
                $models = $command->offset(0)->limit(20)->all();
                foreach ($models as $model) {
                    $item = [];
                    $item['id'] = $model->id;
                    $item['text'] = sprintf("%s-%s-%s", $model->number, $model->extend, $model->domain);
                    $items[] = $item;
                }
            }
            return $this->renderJson(true, ['items' => $items]);
        }
    }

}
