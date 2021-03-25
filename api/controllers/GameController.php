<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\Pagination;
use api\models\Game;

class GameController extends Controller
{
	public function actionIndex()
	{
        $request = Yii::$app->request;
        $form = new \api\forms\FetchGameForm([
            'q' => $request->get('q'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $games = $command->offset($pages->offset)->limit($pages->limit)->all();

        return [
        	'games' => $games,
        	'count' => $pages->totalCount,
        	'limit' => $pages->getPageSize(),
        	'current_page' => $pages->getPage(),
        	'num_pages' => $pages->getPageCount(),
        ];
	}

	public function actionView($id)
	{
		return Game::findOne($id);
	}
}