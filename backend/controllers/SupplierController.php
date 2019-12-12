<?php
namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\forms\FetchSupplierForm;
use backend\forms\FetchSupplierGameForm;
use backend\models\Supplier;
use backend\models\User;
use backend\behaviors\UserSupplierBehavior;

class SupplierController extends Controller
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
            'status' => $request->get('status'),
        ];
        $form = new FetchSupplierForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $command->orderBy(['created_at' => SORT_DESC]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index.php', [
            'models' => $models,
            'search' => $form,
            'pages' => $pages,
        ]);
    }

    public function actionCreate($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = new Supplier();
            $supplier->setScenario(Supplier::SCENARIO_CREATE);
            $supplier->user_id = $id;
            return $this->asJson(['status' => $supplier->save()]);
        }
    }

    public function actionRemove($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = Supplier::findOne($id);
            if (!$supplier) throw new NotFoundHttpException('Not found');
            return $this->asJson(['status' => $supplier->delete()]);
        }
    }

    public function actionEnable($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = Supplier::findOne($id);
            if (!$supplier) throw new NotFoundHttpException('Not found');
            $supplier->setScenario(Supplier::SCENARIO_EDIT);
            $supplier->status = Supplier::STATUS_ENABLED;
            return $this->asJson(['status' => $supplier->save()]);
        }
    }

    public function actionDisable($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = Supplier::findOne($id);
            if (!$supplier) throw new NotFoundHttpException('Not found');
            $supplier->setScenario(Supplier::SCENARIO_EDIT);
            $supplier->status = Supplier::STATUS_DISABLED;
            return $this->asJson(['status' => $supplier->save()]);
        }
    }

    public function actionGame($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'game.my-game';
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException('Not found');
        $user->attachBehavior('supplier', new UserSupplierBehavior);
        if (!$user->isSupplier()) throw new NotFoundHttpException('Not found');
        $command = $user->getSupplierGames();
        $command->with('game');
        $command->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('game', [
            'models' => $models,
            'pages' => $pages,
            'supplier' => $user
        ]); 
    }
}
