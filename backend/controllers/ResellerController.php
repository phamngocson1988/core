<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\User;
use yii\web\NotFoundHttpException;

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
        $this->view->params['main_menu_active'] = 'game.reseller';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $command = User::find()->where([
            'is_reseller' => User::IS_RESELLER,
        ]);
        if ($q) {
            $command->andWhere(['like', 'email', $q]);
        }
        $command->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('index.php', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionPrice($id)
    {
        $this->view->params['main_menu_active'] = 'game.reseller';
        $request = Yii::$app->request;
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException('Not found');
    }
}