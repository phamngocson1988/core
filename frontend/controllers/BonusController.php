<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\models\Bonus;
use frontend\models\Operator;

class BonusController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'operator'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create', 'reply'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $form = new \frontend\forms\FetchBonusForm([
            'bonus_type' => $request->get('bonus_type'),
            'wagering_requirement' => $request->get('wagering_requirement'),
            'minimum_deposit_value' => $request->get('minimum_deposit_value'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $bonuses = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $total = $command->count();
        return $this->render('index', [
            'bonuses' => $bonuses,
            'pages' => $pages,
            'total' => $total,
            'search' => $form
        ]);
    }


    public function actionOperator($id, $slug)
    {
        $operator = Operator::findOne($id);
        $command = Bonus::find()->where(['operator_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $bonuses = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('operator', [
            'bonuses' => $bonuses,
            'pages' => $pages,
            'operator' => $operator,
        ]);
    }
}