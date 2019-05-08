<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\models\Contact;
use common\models\File;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

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

    public function actionImport($id)
    {
        $request = Yii::$app->request;
        $file = File::findOne($id);
        if (!$file) throw new NotFoundHttpException("File không tồn tại", 1);
        $url = $file->getPath();
        $fp = fopen($url, 'r');
        if ($fp) {
            $models = [];
            $first_time = true;
            while (($line = fgetcsv($fp, 1000, ",")) != FALSE) {
                if ($first_time == true) {
                    $first_time = false;
                    continue;
                }
                $model = new Contact();
                $model->user_id = Yii::$app->user->id;
                $model->phone = $line[0];
                $model->name  = $line[1];
                $model->description  = $line[2];
                $models[] = $model;
            }

            if ($request->isPost) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($models as $model) {
                        $model->save();
                    }
                    $transaction->commit();
                    $file->delete();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                    return $this->redirect(['contact/index']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
            return $this->render('import.tpl', [
                'models' => $models,
                'back' => $request->get('ref', Url::to(['contact/index']))
            ]);
        }
    }

    public function actionDownload() 
    {
        $settings = Yii::$app->settings;
        $template = $settings->get('ImportSettingForm', 'import_contact_template', null);
        if (file_exists($template)) {
           Yii::$app->response->sendFile($template);
        } 
    }

    public function actionCall()
    {
        return $this->render('call.tpl');
    }

    public function actionSms()
    {
        return $this->render('sms.tpl');
    }
}
