<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\Pagination;

class UserController extends Controller
{
	public function actionSearch()
	{
        $request = Yii::$app->request;
        $form = new \api\forms\FetchUserForm([
            'q' => $request->get('q'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $users = $command->offset($pages->offset)->limit($pages->limit)->all();

        return [
        	'users' => $users,
        	'count' => $pages->totalCount,
        	'limit' => $pages->getPageSize(),
        	'current_page' => $pages->getPage(),
        	'num_pages' => $pages->getPageCount(),
        ];
	}
}