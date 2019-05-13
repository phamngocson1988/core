<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Contact;
use common\models\File;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use common\models\Record;
use common\models\CustomerDialer;
use common\models\Dialer;
use common\models\TransactionHistory;
use backend\forms\FetchTransactionHistoryForm;

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
        $command = Contact::find()->where(['user_id' => Yii::$app->user->id]);
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
        $this->view->params['main_menu_active'] = 'contact.call';
        $model = new Record();
        $model->setScenario(Record::SCENARIO_CREATE);
        $user = Yii::$app->user->getIdentity();
        $dialers = array_map(function($customerDialer) {
            $dialer = $customerDialer->dialer;
            if ($dialer->action == Dialer::ACTION_CALL) return $dialer;
        }, $user->dialers);
        $dialers = array_filter($dialers);
        $dialers = ArrayHelper::map($dialers, 'id', 'number');
        return $this->render('call', [
            'dialers' => $dialers,
            'model' => $model
        ]);
    }

    public function actionStartCall()
    {
        $request = Yii::$app->request;
        $model = new Record();
        $model->setScenario(Record::SCENARIO_CREATE);
        $model->user_id = Yii::$app->user->id;
        $model->dialer_type = Record::DIALER_TYPE_CALL;
        $model->start_time = date('Y-m-d H:i:s');
        $model->status = Record::STATUS_CALLING;
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, $model);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionEndCall()
    {
        $request = Yii::$app->request;
        $id = $request->post('id');
        $model = Record::findOne($id);
        if (!$model) return;
        $model->setScenario(Record::SCENARIO_EDIT);
        $model->end_time = date('Y-m-d H:i:s');
        $model->status = Record::STATUS_END;
        $model->save();

        $dialer = CustomerDialer::findOne(['dialer_id' => $model->dialer_id, 'user_id' => Yii::$app->user->id]);
        $amount = $dialer->call / 60 * $model->getDuration();
        $history = new TransactionHistory();
        $history->user_id = Yii::$app->user->id;
        $history->amount = $amount;
        $history->description = sprintf("Cuộc gọi đến số %s trong %s giây vào lúc %s", $model->phone, $model->getDuration(), $model->created_at);
        $history->transaction_type = TransactionHistory::TYPE_OUTPUT;
        $history->created_by = Yii::$app->user->id;
        $history->save();
        return $this->renderJson(true);
        // Yii::warning('Your browser was closed at ' . date('Y-m-d H:i:s'), 'call');
    }

    public function actionSms()
    {
        $this->view->params['main_menu_active'] = 'contact.sms';
        return $this->render('sms.tpl');
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $command = Contact::find()->where(['user_id' => Yii::$app->user->id]);
                $command->andWhere(['like', 'phone', $keyword]);
                $models = $command->offset(0)->limit(20)->all();
                if ($models) {
                    foreach ($models as $model) {
                        $item = [];
                        $item['id'] = $model->phone;
                        $item['text'] = sprintf("%s - %s", $model->phone, $model->name);
                        $items[] = $item;
                    }
                } else {
                    $item = [];
                    $item['id'] = $keyword;
                    $item['text'] = $keyword;
                    $items[] = $item;
                }
            }
            return $this->renderJson(true, ['items' => $items]);
        }
    }

    public function actionHistory()
    {
        $this->view->params['main_menu_active'] = 'contact.history';
        $id = Yii::$app->user->id;
        $request = Yii::$app->request;
        $get = $request->get();
        $search = new FetchTransactionHistoryForm([
            'customer_id' => $id
        ]);
        $search->load($get, '');
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('history', [
            'models' => $models,
            'pages' => $pages,
            'search' => $search,
            'customer_id' => $id
        ]);
    }
}
