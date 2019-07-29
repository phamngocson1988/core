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
use backend\models\UserAffiliate;

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
        $command = UserAffiliate::find()->where(['status' => UserAffiliate::STATUS_ENABLE]);
        $command->with('user');
        $command->orderBy(['created_at' => SORT_DESC]);
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
        $command = UserAffiliate::find()->where(['status' => UserAffiliate::STATUS_DISABLE]);
        $command->with('user');
        $command->orderBy(['created_at' => SORT_DESC]);
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
            $userAffiliate = UserAffiliate::findOne($id);
            if (!$userAffiliate) throw new NotFoundHttpException('Not found');
            $userAffiliate->generateAffiliateCode();
            $userAffiliate->status = UserAffiliate::STATUS_ENABLE;
            return $this->asJson(['status' => $userAffiliate->save()]);
        }
    }

    public function actionDowngrade($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $userAffiliate = UserAffiliate::findOne($id);
            if (!$userAffiliate) throw new NotFoundHttpException('Not found');
            return $this->asJson(['status' => $userAffiliate->delete()]);
        }
    }
}