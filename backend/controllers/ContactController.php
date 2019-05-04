<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\models\Contact;
use yii\data\Pagination;

/**
 * ContactController
 */
class ContactController extends Controller
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
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'contact.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $command = Contact::find(['user_id' => Yii::$app->user->id]);
        if ($q) {
             $command->orWhere(['like', 'phone', $q]);
             $command->orWhere(['like', 'name', $q]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'contact.index';
        $request = Yii::$app->request;
        $model = new Contact();
        $model->setScenario(Contact::SCENARIO_CREATE);
        $model->user_id = Yii::$app->user->id;
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['contact/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['contact/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'contact.index';
        $request = Yii::$app->request;
        $model = Contact::findOne($id);
        $model->setScenario(Contact::SCENARIO_EDIT);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['contact/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['contact/index']))
        ]);
    }
}
