<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use backend\forms\FetchSystemLogForm;
use yii\helpers\Url;

class SystemLogController extends Controller
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'system-log.index';
        $request = Yii::$app->request;
        $user_id = $request->get('user_id');
        $action = $request->get('action');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $description = $request->get('description');
        $condition = [
            'user_id' => $user_id,
            'action' => $action,
            'from_date' => strtotime($from_date),
            'to_date' => strtotime($to_date),
            'description' => $description
        ];
        $form = new FetchSystemLogForm($condition);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionIndex1()
    {
        $this->view->params['main_menu_active'] = 'system-log.index';
        $request = Yii::$app->request;
        $user_id = $request->get('user_id');
        $action = $request->get('action');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $condition = [
            'user_id' => $user_id,
            'action' => $action,
            'from_date' => strtotime($from_date),
            'to_date' => strtotime($to_date)
        ];
        $form = new FetchSystemLogForm($condition);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        return $this->render('index1.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }
}
