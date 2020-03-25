<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;


class ReportProfitController extends Controller
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

    public function actionOrder()
    {
        $this->view->params['main_menu_active'] = 'report.cost.order';
        $request = Yii::$app->request;
        $data = [
            'id' => $request->get('id'),
            'confirmed_from' => $request->get('confirmed_from'),
            'confirmed_to' => $request->get('confirmed_to'),
            'payment_method' => $request->get('payment_method'),
        ];
        $form = new \backend\forms\ReportOrderProfitForm($data);
        $command = $form->getCommand();
        $command->with('user');
        $command->with('order');
        $command->with('game');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();

        return $this->render('order.php', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }
}
