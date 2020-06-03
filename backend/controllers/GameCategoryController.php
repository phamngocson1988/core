<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\GameCategory;
use backend\models\GameCategoryItem;

class GameCategoryController extends Controller
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
        $this->view->params['main_menu_active'] = 'game-category.index';
        $request = Yii::$app->request;
        $models = GameCategory::find()->all();
        $categoryIds = ArrayHelper::getColumn($models, 'id');
        $count = GameCategoryItem::find()->select(['category_id', 'COUNT(game_id) as count'])
        ->where(['in', 'category_id', $categoryIds])->asArray()->all();
        $gameCount = ArrayHelper::map($count, 'category_id', 'count');

        return $this->render('index', [
            'models' => $models,
            'gameCount' => $gameCount
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'game-category.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateGameCategoryForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['game-category/index']));
                return $this->redirect($ref);
            }
        }
        return $this->render('create.tpl', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'game-category.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditGameCategoryForm(['id' => $id]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                $ref = $request->get('ref', Url::to(['game-category/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData();
        }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['game-category/index']))
        ]);

    }


}
