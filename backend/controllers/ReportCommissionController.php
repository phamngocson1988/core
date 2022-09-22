<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\User;


class ReportCommissionController extends Controller
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

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'report.commission.index';
        $form = new \backend\forms\ReportCommissionForm(['role' => 'orderteam']);
        $models = $form->fetch();
        return $this->render('index', [
            'models' => $models,
            'search' => $form,
        ]);
        return $this->render('index');
    }
}
