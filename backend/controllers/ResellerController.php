<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\User;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Game;
use backend\models\GameReseller;
use backend\forms\FetchResellerForm;
use backend\behaviors\UserResellerBehavior;

class ResellerController extends Controller
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
        $this->view->params['main_menu_active'] = 'reseller.index';
        $request = Yii::$app->request;

        $data = [
            'user_id' => $request->get('user_id'),
            'manager_id' => $request->get('manager_id'),
            'phone' => $request->get('phone'),
        ];
        $form = new FetchResellerForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $command->orderBy(['created_at' => SORT_DESC]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $changeLevelService = new \backend\forms\ChangeResellerLevelForm();

        return $this->render('index.php', [
            'models' => $models,
            'search' => $form,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
            'changeLevelService' => $changeLevelService
        ]);
    }

    public function actionCreate($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateResellerForm(['user_id' => $id]);
        if ($model->load($request->post()) && $model->process()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $model->getFirstErrorMessage()]);
        }
    }

    public function actionUpgrade($id)
    {
        $user = User::findOne($id);
        $user->on(User::EVENT_AFTER_UPDATE, function ($event) {
            $model = $event->sender;
            $model->attachBehavior('reseller', UserResellerBehavior::className());
            $model->upgrade();
        });
        $nextLevel = in_array($user->reseller_level + 1, [User::RESELLER_LEVEL_1, User::RESELLER_LEVEL_2, User::RESELLER_LEVEL_3]) ? $user->reseller_level + 1 : $user->reseller_level;
        $user->old_reseller_level = $user->reseller_level;
        $user->reseller_updated_at = date('Y-m-d H:i:s');
        $user->reseller_level = $nextLevel;
        $user->save(false, ['reseller_level', 'old_reseller_level', 'reseller_updated_at']);
        Yii::$app->session->setFlash('success', sprintf("User %s have upgraded to level %s", $user->name, $user->getResellerLabel()));
        return $this->asJson(['status' => true]);
    }

    public function actionDowngrade($id)
    {
        $user = User::findOne($id);
        $user->on(User::EVENT_AFTER_UPDATE, function ($event) {
            $model = $event->sender;
            $model->attachBehavior('reseller', UserResellerBehavior::className());
            $model->downgrade();
        });
        $nextLevel = in_array($user->reseller_level - 1, [User::RESELLER_LEVEL_1, User::RESELLER_LEVEL_2, User::RESELLER_LEVEL_3]) ? $user->reseller_level - 1 : $user->reseller_level;
        $user->old_reseller_level = $user->reseller_level;
        $user->reseller_updated_at = date('Y-m-d H:i:s');
        $user->reseller_level = $nextLevel;
        $user->save(false, ['reseller_level', 'old_reseller_level', 'reseller_updated_at']);
        Yii::$app->session->setFlash('success', sprintf("User %s have downgraded to level %s", $user->name, $user->getResellerLabel()));
        return $this->asJson(['status' => true]);
    }

    public function actionChangeLevel($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\ChangeResellerLevelForm(['user_id' => $id]);
        if ($model->load($request->post()) && $model->process()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $model->getFirstErrorMessage()]);
        }
    }

    public function actionDelete($id)
    {
        $user = User::findOne($id);
        $user->on(User::EVENT_AFTER_UPDATE, function ($event) {
            $model = $event->sender;
            $model->attachBehavior('reseller', UserResellerBehavior::className());
            $model->deleteReseller();
        });
        $user->is_reseller = User::IS_NOT_RESELLER;
        $user->save(true, ['is_reseller']);
        // $user->removeGameResellers();
        Yii::$app->session->setFlash('success', "Removed reseller role for user $user->name");
        return $this->asJson(['status' => true]);
    }

    public function actionAssign($id)
    {
        $request = Yii::$app->request;
        $manager_id = $request->post('manager_id');
        $user = User::findOne($id);
        $user->attachBehavior('reseller', UserResellerBehavior::className());
        $user->assignManager($manager_id);
        return $this->asJson(['status' => true]);
    }
}