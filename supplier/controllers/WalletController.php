<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use supplier\models\SupplierWithdrawRequest;
use supplier\models\SupplierBank;
use supplier\models\SupplierWallet;
use supplier\models\OrderSupplier;
use supplier\behaviors\UserSupplierBehavior;
use supplier\forms\FetchWalletForm;

class WalletController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->isAdvanceMode();
                        },

                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'wallet.index';
        $request = Yii::$app->request;
        $search = new FetchWalletForm([
            'supplier_id' => Yii::$app->user->id,
            'type' => $request->get('type'),
            'created_at_from' => $request->get('created_at_from'),
            'created_at_to' => $request->get('created_at_to'),
        ]);
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();

        // Filter request
        $orderIncome = array_filter($models, function($model) {
            return $model->source == 'order' && $model->type == SupplierWallet::TYPE_INPUT && $model->status == SupplierWallet::STATUS_COMPLETED;
        });
        $orderIds = ArrayHelper::getColumn($orderIncome, 'key');
        $supplierRequest = OrderSupplier::find()->where(["IN", "order_id", $orderIds])
        ->andWhere(["status" => OrderSupplier::STATUS_CONFIRMED])->all();
        $supplierRequestMapping = ArrayHelper::map($supplierRequest, 'order_id', 'id');
        return $this->render('index', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages,
            'supplierRequestMapping' => $supplierRequestMapping
        ]);
    }
}
