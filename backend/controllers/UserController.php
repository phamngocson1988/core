<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\SignupForm;
use backend\forms\FetchUserForm;
use backend\forms\ChangeUserStatusForm;

/**
 * UserController
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'change-status'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['suggestion'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'user.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status', '');
        $form = new FetchUserForm(['q' => $q, 'status' => $status]);

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $links = [
            'delete' => Url::to(['user/change-status', 'status' => 'delete']),
            'active' => Url::to(['user/change-status', 'status' => 'active'])
        ];

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'links' => $links
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'user.index';        
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $model = new SignupForm();
        if ($model->load($request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        return $this->render('create.tpl', [
            'model' => $model,
            'roles' => $roles,
            'back' => $request->get('ref', Url::to(['user/index']))
        ]);
    }

    public function actionSuggestion()
    {
        $request = Yii::$app->request;

        if( $request->isAjax) {
            $keyword = $request->get('q');
            $items = [];
            if ($keyword) {
                $form = new FetchUserForm(['q' => $keyword]);
                $command = $form->getCommand();
                $users = $command->offset(0)->limit(20)->all();
                foreach ($users as $user) {
                    $item = [];
                    $item['id'] = $user->id;
                    $item['text'] = sprintf("%s - %s", $user->username, $user->email);
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
            $form = new ChangeUserStatusForm(['id' => $id]);
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
