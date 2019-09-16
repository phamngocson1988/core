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
}