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
        $command = User::find()->where([
            'is_reseller' => User::IS_RESELLER,
        ]);
        $command->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionUpgrade($id)
    {
        $user = User::findOne($id);
        $nextLevel = in_array($user->reseller_level + 1, [User::RESELLER_LEVEL_1, User::RESELLER_LEVEL_2, User::RESELLER_LEVEL_3]) ? $user->reseller_level + 1 : $user->reseller_level;
        $user->reseller_level = $nextLevel;
        $user->save(false, ['reseller_level']);
        Yii::$app->session->setFlash('success', sprintf("User %s have upgraded to level %s", $user->name, $user->getResellerLabel()));
        return $this->asJson(['status' => true]);
    }

    public function actionDowngrade($id)
    {
        $user = User::findOne($id);
        $nextLevel = in_array($user->reseller_level - 1, [User::RESELLER_LEVEL_1, User::RESELLER_LEVEL_2, User::RESELLER_LEVEL_3]) ? $user->reseller_level - 1 : $user->reseller_level;
        $user->reseller_level = $nextLevel;
        $user->save(false, ['reseller_level']);
        Yii::$app->session->setFlash('success', sprintf("User %s have downgraded to level %s", $user->name, $user->getResellerLabel()));
        return $this->asJson(['status' => true]);
    }

    public function actionDelete($id)
    {
        $user = User::findOne($id);
        $user->is_reseller = User::IS_NOT_RESELLER;
        $user->save(false, ['is_reseller']);
        $user->removeGameResellers();
        Yii::$app->session->setFlash('success', "Removed reseller role for user $user->name");
        return $this->asJson(['status' => true]);
    }

    // public function actionCreate()
    // {
    //     $this->view->params['main_menu_active'] = 'game.reseller';
    //     $request = Yii::$app->request;
    //     if ($request->isPost) {
    //         $userId = $request->post('user_id');
    //         $user = User::findOne($userId);
    //         $user->is_reseller = User::IS_RESELLER;
    //         $user->save(false, ['is_reseller']);
    //         return $this->redirect(['reseller/index']);
    //     }
    //     return $this->render('create.php', [
    //         'model' => new User
    //     ]);
    // }

    public function actionPrice()
    {
        $this->view->params['main_menu_active'] = 'reseller.price';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $command = Game::find();
        $command->where(['<>', 'status', Game::STATUS_DELETE]);
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('price', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    // public function actionCreatePrice($id)
    // {
    //     $request = Yii::$app->request;
    //     $user = User::findOne($id);
    //     if (!$user) throw new NotFoundHttpException('Not found');
    //     $model = new GameReseller();
    //     if ($model->load($request->post()) && $model->save()) {
    //         return $this->asJson(['status' => true]);
    //     } else {
    //         return $this->asJson(['status' => false, 'errors' => $model->getErrorSummary(true)]);
    //     }
    // }

    // public function actionEditPrice($game_id, $reseller_id)
    // {
    //     $request = Yii::$app->request;
    //     $model = GameReseller::findOne(['game_id' => $game_id, 'reseller_id' => $reseller_id]);
    //     if (!$model) throw new NotFoundHttpException('Not found price');
    //     if ($model->load($request->post()) && $model->save()) {
    //         return $this->asJson(['status' => true]);
    //     } else {
    //         return $this->asJson(['status' => false, 'errors' => $model->getErrorSummary(true)]);
    //     }
    // }

    
}