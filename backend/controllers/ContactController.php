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
use common\components\telecom\Tel4vn;
use common\models\Group;
use common\models\ContactGroup;
use backend\forms\FetchContactForm;

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
        $group_ids = $request->get('group_ids');
        $search = new FetchContactForm([
            'q' => $q, 
            'group_ids' => $group_ids,
            'user_id' => Yii::$app->user->id
        ]);
        $command = $search->getCommand();
        // $command = Contact::find()->where(['user_id' => Yii::$app->user->id]);
        // if ($q) {
        //     $command->andWhere(['or',
        //         ['like', 'phone', $q],
        //         ['like', 'name', $q]
        //     ]);
        // }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'search' => $search,
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
            $groupIds = (array)$model->group_ids;
            $groupIds = array_filter($groupIds);
            foreach ($groupIds as $groupId) {
                $contactGroup = new ContactGroup();
                $contactGroup->contact_id = $model->id;
                $contactGroup->group_id = $groupId;
                $contactGroup->save();
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['contact/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        $groupList = ArrayHelper::map(Group::find()->all(), 'id', function($m) {
            return $m->name . '<span></span>';
        });

        return $this->render('create.tpl', [
            'model' => $model,
            'groupList' => $groupList,
            'back' => $request->get('ref', Url::to(['contact/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'contact.index';
        $request = Yii::$app->request;
        $model = Contact::findOne($id);
        $model->setScenario(Contact::SCENARIO_EDIT);
        $model->group_ids = ArrayHelper::map($model->contactGroups, 'id', 'group_id');
        if ($model->load($request->post()) && $model->save()) {
            $model->deleteGroups();
            $groupIds = $model->group_ids;
            foreach ($groupIds as $groupId) {
                $contactGroup = new ContactGroup();
                $contactGroup->contact_id = $model->id;
                $contactGroup->group_id = $groupId;
                $contactGroup->save();
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            return $this->redirect(['contact/index']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        $groupList = ArrayHelper::map(Group::find()->all(), 'id', function($m) {
            return $m->name . '<span></span>';
        });

        return $this->render('edit.tpl', [
            'model' => $model,
            'groupList' => $groupList,
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
        $contacts = $user->contacts;
        $groups = Group::find()->all();
        return $this->render('call', [
            'dialers' => $dialers,
            'contacts' => $contacts,
            'model' => $model,
            'groups' => $groups
        ]);
    }

    public function actionStartCall()
    {
        $request = Yii::$app->request;
        $contactIds = $request->post('phones', []);
        $contacts = Contact::findAll($contactIds);
        $phones = ArrayHelper::getColumn($contacts, 'phone');
        $phones = array_filter($phones);
        $total = count($phones);
        $success = $failure = [];
        foreach ($phones as $phone) {
            $model = new Record();
            $model->setScenario(Record::SCENARIO_CREATE);
            $model->user_id = Yii::$app->user->id;
            $model->dialer_type = Record::DIALER_TYPE_CALL;
            $model->start_time = date('Y-m-d H:i:s');
            $model->status = Record::STATUS_CALLING;
            $model->phone = $phone;
            if ($model->load($request->post()) && $model->save()) {
                $caller = new Tel4vn();
                $dialer = $model->dialer;
                $caller->setSetting($dialer);
                $result = $caller->call($model->phone, $model->message);
                $success[$phone] = $result;
            } else {
                $failure[] = $phone;
            }
        }
        return $this->renderJson(true, ['total' => $total, 'success' => $success, 'failure' => $failure]);
    }

    public function actionEndCall()
    {
        $request = Yii::$app->request;
        $models = Record::findAll(['status' => Record::STATUS_CALLING]);
        foreach ($models as $model) {
            $model->setScenario(Record::SCENARIO_EDIT);
            $model->end_time = date('Y-m-d H:i:s');
            $model->status = Record::STATUS_END;
            $model->save();
    
            $dialer = CustomerDialer::findOne(['dialer_id' => $model->dialer_id, 'user_id' => Yii::$app->user->id]);
            $amount = $dialer->call;//$dialer->call / 60 * $model->getDuration();
            $history = new TransactionHistory();
            $history->user_id = Yii::$app->user->id;
            $history->amount = $amount;
            $history->description = sprintf("Cuộc gọi đến số %s trong %s giây vào lúc %s", $model->phone, $model->getDuration(), $model->created_at);
            $history->transaction_type = TransactionHistory::TYPE_OUTPUT;
            $history->created_by = Yii::$app->user->id;
            $history->save();
        }
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

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = Contact::findOne($id);
        if (!$model) throw new NotFoundHttpException('Not found', 404);
        if ($model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionDeleteAll($ids)
    {
        $request = Yii::$app->request;
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $model = Contact::findOne($id);
            $model->delete();
        }
        return $this->renderJson(true, $ids);
    }
}
