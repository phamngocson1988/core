<?php
namespace client\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use client\forms\FetchPostForm;
use client\forms\CreatePostForm;
use client\forms\EditPostForm;
use client\forms\DeletePostForm;
use yii\data\Pagination;
use client\forms\ChangePostPositionForm;
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

    public function actionChangePosition($id)
    {
        $request = Yii::$app->request;
        $direction = $request->get('direct');
        $form = new ChangePostPositionForm(['id' => $id, 'direction' => $direction]);
        if (!$form->process()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'error'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        $ref = $request->get('ref', Url::to(['post/index']));
        return $this->redirect($ref);
    }
}
