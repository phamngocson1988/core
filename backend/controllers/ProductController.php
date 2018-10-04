<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchProductForm;
use backend\forms\CreateProductForm;
use backend\forms\EditProductForm;
use backend\forms\DeleteProductForm;
use yii\helpers\Url;

class ProductController extends Controller
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
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $gameId = $request->get('game_id', null);
        $ref = $request->get('ref', Url::to(['game/index']));

        if (!$gameId) {
            return $this->redirect($ref);
        }

        $form = new FetchProductForm(['game_id' => $gameId]);
        if (!$form->validate()) {
            Yii::$app->session->setFlash('error', $form->getErrorSummary(true));
            return $this->redirect($ref);
        }
        $products = $form->fetch();
        $editProductForms = array_map(function($product){
            $editForm = new EditProductForm();
            $editForm->setProduct($product);
            $editForm->loadData($product->id);
            return $editForm;
        }, $products);
        $game = $form->getGame();

        $newProductForm = new CreateProductForm(['game_id' => $gameId]);

        return $this->render('index.tpl', [
            'editProductForms' => $editProductForms,
            'newProductForm' => $newProductForm,
            'game' => $game,
            'ref' => $ref
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new CreateProductForm();
        if ($request->getIsAjax()) {
            if ($model->load($request->post())) {
                $result = $model->save();
                return $this->renderJson($result !== false, ['model' => $model], $model->getErrorSummary(true));
            }    
        }
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $model = new EditProductForm();
        if ($request->getIsAjax()) {
            $model->load($request->post());
            return $this->renderJson($model->save(), ['model' => $model], $model->getErrorSummary(true));
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->getIsAjax()) {
            $form = new DeleteProductForm(['id' => $id]);
            return $this->renderJson($form->delete(), [], $form->getErrorSummary(true));
        }
        return $this->redirectNotFound();
    }
    
}
