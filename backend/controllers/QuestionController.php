<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\Question;
use backend\models\QuestionCategory;

class QuestionController extends Controller
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
    
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'question.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $command = Question::find();

        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'question.index';
        $request = Yii::$app->request;
        $model = new Question();
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Success!');
            $ref = $request->get('ref', Url::to(['question/index']));
            return $this->redirect($ref);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        return $this->render('create', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['question/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'question.index';
        $request = Yii::$app->request;
        $model = Question::findOne($id);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Success!');
            $ref = $request->get('ref', Url::to(['question/index']));
            return $this->redirect($ref);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }
        return $this->render('edit', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['question/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = Question::findOne($id);
        if ($model && $model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionCategory()
    {
        $this->view->params['main_menu_active'] = 'question.category';
        $request = Yii::$app->request;
        $command = QuestionCategory::find();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();

        return $this->render('category', [
            'models' => $models,
            'pages' => $pages,
            'newModel' => new QuestionCategory(),
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreateCategory()
    {
        $this->view->params['main_menu_active'] = 'question.category';
        $request = Yii::$app->request;
        $model = new QuestionCategory();
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Tạo danh mục thành công');
            return $this->redirect('question/category');
        } else {
            Yii::$app->session->setFlash('error', $model->getErrors());
        }
        return $this->render('create-category.tpl', ['model' => $model]);
    }

    public function actionEditCategory($id)
    {
        $this->view->params['main_menu_active'] = 'question.category';
        $request = Yii::$app->request;
        $model = QuestionCategory::findOne($id);
        if ($model->load($request->post()) && $model->save()) {
            // return $this->renderJson(true, []);
            Yii::$app->session->setFlash('success', 'Tạo danh mục thành công');
            return $this->redirect(['question/category']);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrors());
            // return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
        return $this->render('edit-category.tpl', ['model' => $model]);
    }

    public function actionDeleteCategory($id)
    {
        $request = Yii::$app->request;
        $model = QuestionCategory::findOne($id);
        if ($model && $model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }
}
