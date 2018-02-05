<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\FetchCustomerForm;
use backend\forms\ChangeCustomerStatusForm;

/**
 * CustomerController
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['change-status'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'suggestion'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'customer.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status', '');
        $form = new FetchCustomerForm(['q' => $q, 'status' => $status]);

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $links = [
            'delete' => Url::to(['customer/change-status', 'status' => 'delete']),
            'active' => Url::to(['customer/change-status', 'status' => 'active'])
        ];

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'links' => $links
        ]);
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $form = new FetchCustomerForm(['q' => $keyword]);
                $command = $form->getCommand();
                $customers = $command->offset(0)->limit(20)->all();
                foreach ($customers as $customer) {
                    $item = [];
                    $item['id'] = $customer->id;
                    $item['text'] = sprintf("%s - %s", $customer->username, $customer->email);
                    $items[] = $item;
                }
            }
            return $this->renderJson(true, ['items' => $items]);
        }
    }

    public function actionChangeStatus()
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $id = $request->get('id');
            $status = $request->get('status');
            $form = new ChangeCustomerStatusForm(['id' => $id]);
            switch ($status) {
                case 'active':
                    $result = $form->active();
                    break;
                case 'delete':
                    $result = $form->delete();
                    break;                
                default:
                    $result = false;
                    break;
            }
            return $this->renderJson($result, null, $form->getErrors());
        }
    }
}
