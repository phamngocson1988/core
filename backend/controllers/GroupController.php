<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use common\models\Group;
use common\models\ContactGroup;
use yii\helpers\ArrayHelper;
/**
 * GroupController
 */
class GroupController extends Controller
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
        $this->view->params['main_menu_active'] = 'group.index';
        $request = Yii::$app->request;
        $command = Group::find();
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
        $this->view->params['main_menu_active'] = 'group.index';
        $request = Yii::$app->request;
        $model = new Group();
        $model->setScenario(Group::SCENARIO_CREATE);        
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['group/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['group/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'group.index';
        $request = Yii::$app->request;
        $model = Group::findOne($id);
        $model->setScenario(Group::SCENARIO_EDIT);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['group/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['group/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = Group::findOne($id);
        if (!$model) throw new NotFoundHttpException('Not found', 404);
        if ($model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $command = Group::find();
                $command->orWhere(['like', 'name', $keyword]);
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
