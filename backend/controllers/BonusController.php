<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

use backend\forms\FetchBonusForm;
use backend\forms\CreateBonusForm;
use backend\forms\EditBonusForm;


class BonusController extends Controller
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
        $this->view->params['main_menu_active'] = 'bonus.index';
        $request = Yii::$app->request;
        $form = new FetchBonusForm([
            'q' => $request->get('q'),
            'operator_id' => $request->get('operator_id'),
        ]);
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
        $this->view->params['main_menu_active'] = 'bonus.index';
        $request = Yii::$app->request;
        $model = new CreateBonusForm(['language' => $language]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['bonus/index']);
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
        $this->view->params['main_menu_active'] = 'bonus.index';
        $request = Yii::$app->request;
        $model = new EditBonusForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['bonus/index']);
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
