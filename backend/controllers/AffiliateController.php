<?php
namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\User;

/**
 * AffiliateController
 */
class AffiliateController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'user.affiliate';
        $command = User::find()->where(['IS NOT', 'affiliate_code', null]);
        $command->orderBy(['affiliate_request_time' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionRequest()
    {
        $this->view->params['main_menu_active'] = 'user.affiliate';
        $command = User::find()->where(['affiliate_request' => 1]);
        $command->orderBy(['affiliate_request_time' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('request', [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionUpgrade($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $user->affiliate_code = Yii::$app->security->generateRandomString(6);
            $user->affiliate_request = 0;
            $user->affiliate_request_time = date('Y-m-d H:i:s');
            return $this->asJson(['status' => $user->save(true, ['affiliate_code', 'affiliate_request', 'affiliate_request_time'])]);
        }
    }

    public function actionDowngrade($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $user->affiliate_code = null;
            $user->affiliate_request = 0;
            $user->affiliate_request_time = date('Y-m-d H:i:s');
            return $this->asJson(['status' => $user->save(true, ['affiliate_code', 'affiliate_request', 'affiliate_request_time'])]);
        }
    }
}