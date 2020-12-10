<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

use backend\models\ComplainReason;

class ReasonController extends Controller
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
                        'roles' => ['system'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'reason.index';
        $request = Yii::$app->request;
        $language = $request->get('language');
        $form = new \backend\forms\FetchReasonForm(['language' => $language]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionCreate($language)
    {
        $this->view->params['main_menu_active'] = 'reason.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateComplainReasonForm(['language' => $language]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['reason/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'reason.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditComplainReasonForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['reason/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);

    }
}
