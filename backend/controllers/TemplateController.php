<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use common\models\Template;
use yii\helpers\ArrayHelper;
/**
 * TemplateController
 */
class TemplateController extends Controller
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
        $this->view->params['main_menu_active'] = 'template.index';
        $request = Yii::$app->request;
        $command = Template::find();
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
        $this->view->params['main_menu_active'] = 'template.index';
        $request = Yii::$app->request;
        $model = new Template();
        $model->setScenario(Template::SCENARIO_CREATE);        
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['template/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['template/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'template.index';
        $request = Yii::$app->request;
        $model = Template::findOne($id);
        $model->setScenario(Template::SCENARIO_EDIT);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['template/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['template/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = Template::findOne($id);
        if (!$model) throw new NotFoundHttpException('Not found', 404);
        if ($model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }
}
