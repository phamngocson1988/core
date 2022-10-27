<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

// models
use website\models\QuestionCategory;
use website\models\Question;

class QuestionController extends Controller
{
    public function behaviors()
    {
        return [
            'blockip' => [
                'class' => \website\components\filters\BlockIpAccessControl::className(),
            ],
        ];
    }

	public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'question.index';
        $request = Yii::$app->request;
        $categories = QuestionCategory::find()->orderBy(['position' => SORT_ASC])->all();
        $countQuestions = Question::find()->select(['category_id', 'COUNT(*) as count'])->groupBy(['category_id'])->asArray()->all();
        $stat = ArrayHelper::map($countQuestions, 'category_id', 'count');

        return $this->render('index', [
        	'categories' => $categories,
        	'stat' => $stat
        ]);
    }

    public function actionList($id)
    {
        $this->view->params['main_menu_active'] = 'question.index';
        $request = Yii::$app->request;
        $keyword = $request->get('keyword');
        $command = Question::find()->where(['category_id' => $id]);
        if ($keyword) {
            $command->orFilterWhere(['like', 'title', $keyword])
              ->orFilterWhere(['like', 'content', $keyword]);
        }
        $questions = $command->all();
        $category = QuestionCategory::findOne($id);
        return $this->render('list', [
            'questions' => $questions,
            'category' => $category,
        ]);
    }
}