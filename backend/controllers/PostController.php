<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchPostForm;
use backend\forms\CreatePostForm;
use backend\forms\EditPostForm;
use backend\forms\DeletePostForm;
use yii\data\Pagination;
use backend\forms\FetchCategoryForm;
use backend\forms\CreateCategoryForm;
use backend\forms\EditCategoryForm;
use backend\forms\DeleteCategoryForm;
use backend\forms\ChangePostPositionForm;
use yii\helpers\Url;
use common\models\Post;

class PostController extends Controller
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
     * Show the list of posts
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'post.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $form = new FetchPostForm(['q' => $q]);

        $command = $form->getCommand();

        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['position' => SORT_DESC])
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
        $this->view->params['main_menu_active'] = 'post.index';
        $request = Yii::$app->request;
        $model = new CreatePostForm();
        $model->type = Post::POST_TYPE_POST;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['post/index']));
                return $this->redirect($ref);
            }
        }

        $this->view->registerJsFile('@web/js/ckeditor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['post/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'post.index';
        $request = Yii::$app->request;
        $model = new EditPostForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['post/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }
        $this->view->registerJsFile('@web/js/ckeditor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['post/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeletePostForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['post/index']));
        return $this->redirect($ref);
    }

    public function actionCategory()
    {
        $this->view->params['main_menu_active'] = 'post.category';
        $request = Yii::$app->request;
        $form = new FetchCategoryForm(['type' => 'post']);
        $models = $form->fetch();
        return $this->render('category.tpl', [
            'models' => $models,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreateCategory()
    {
        $this->view->params['main_menu_active'] = 'post.category';
        $request = Yii::$app->request;
        $model = new CreateCategoryForm(['type' => 'post']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['post/category']));
                return $this->redirect($ref);
            }
        }
        return $this->render('create-category.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['post/category']))
        ]);
    }

    public function actionEditCategory($id)
    {
        $this->view->params['main_menu_active'] = 'post.category';
        $request = Yii::$app->request;
        $model = new EditCategoryForm(['type' => 'post']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['post/category']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }

        return $this->render('edit-category.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['post/category']))
        ]);

    }

    public function actionDeleteCategory($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteCategoryForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['post/category']));
        return $this->redirect($ref);
    }
    
    public function actionChangePosition($id)
    {
        $request = Yii::$app->request;
        $direction = $request->get('direct');
        $form = new ChangePostPositionForm(['id' => $id, 'direction' => $direction]);
        if (!$form->process()) {
            Yii::$app->session->setFlash('error', Yii::t('app/error', 'error'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['post/index']));
        return $this->redirect($ref);
    }
}
