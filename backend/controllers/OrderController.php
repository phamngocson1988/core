<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchOrderForm;
use backend\forms\CreateOrderForm;
use backend\forms\EditOrderForm;
use backend\forms\DeleteOrderForm;
use yii\data\Pagination;
use backend\forms\ChangeOrderPositionForm;
use yii\helpers\Url;
use common\models\Order;

class OrderController extends Controller
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

    /**
     * Show the list of orders
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $form = new FetchOrderForm();

        $command = $form->getCommand();

        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $model = new CreateOrderForm();
        $model->type = Order::ORDER_TYPE_ORDER;
        if ($model->load(Yii::$app->request->order())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['order/index']));
                return $this->redirect($ref);
            }
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['order/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $model = new EditOrderForm();
        if ($model->load(Yii::$app->request->order())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['order/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['order/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteOrderForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrorSummary(true));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['order/index']));
        return $this->redirect($ref);
    }

    public function actionChangePosition($id)
    {
        $request = Yii::$app->request;
        $direction = $request->get('direct');
        $form = new ChangeOrderPositionForm(['id' => $id, 'direction' => $direction]);
        if (!$form->process()) {
            Yii::$app->session->setFlash('error', $form->getErrorSummary(true));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['order/index']));
        return $this->redirect($ref);
    }
}
