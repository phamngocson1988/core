<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Operator;
use frontend\models\OperatorFavorite;
use frontend\models\ForumTopic;
use frontend\models\ForumPost;

class MemberController extends Controller
{
	public function actionIndex($username)
	{
		$request = Yii::$app->request;
		$user = User::find()->where(['username' => $username])->one();

		// follow
		$operatorTable = Operator::tableName();
        $favoriteTable = OperatorFavorite::tableName();
        $userId = $user->id;
        $favoriteCommand = Operator::find()
        ->innerJoin($favoriteTable, "{$favoriteTable}.operator_id = {$operatorTable}.id AND {$favoriteTable}.user_id = $userId")
        ->limit(8);
        $favorites = $favoriteCommand->all();
        $totalFavorite = $favoriteCommand->count();

        // topic
        $posts = ForumPost::find()
        ->where(['created_by' => $userId])
        ->orderBy(['id' => SORT_DESC])
        ->limit(20)
        ->with('topic')
        ->all();
		return $this->render('index', [
			'user' => $user,
			'favorites' => $favorites,
			'totalFavorite' => $totalFavorite,
			'posts' => $posts,
		]);
	}
}