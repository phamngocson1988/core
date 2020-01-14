<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use supplier\models\SupplierGameSuggestion;

class SuggestController extends Controller
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
     * Show the list of posts
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'suggest.index';
        $request = Yii::$app->request;
        $command = SupplierGameSuggestion::find([
            'created_by' => Yii::$app->user->id
        ]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'suggest.index';
        $request = Yii::$app->request;
        $model = new SupplierGameSuggestion();
        $model->setScenario(SupplierGameSuggestion::SCENARIO_CREATE);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['suggest/index']));
                return $this->redirect($ref);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['suggest/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'suggest.index';
        $request = Yii::$app->request;
        $model = SupplierGameSuggestion::findOne($id);
        $model->setScenario(SupplierGameSuggestion::SCENARIO_EDIT);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['suggest/index']));
                return $this->redirect($ref);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['suggest/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = SupplierGameSuggestion::findOne($id);
        if (!$model) throw new Exception("Not found", 1);
        return $this->asJson(['status' => $model->delete()]);
        
    }

}
