<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\CreateProductForm;

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

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new CreateProductForm();
        if ($request->getIsAjax()) {
            if ($model->load($request->post())) {
                if ($model->save()) {
                    return $this->renderJson(true, [
                        'model' => $model,
                    ]);
                }
            }    
            return $this->renderJson(true, ['model' => $model], $model->getErrors());
        }
        return $this->renderPartial('_partial_create.tpl', [
            'model' => $model
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $model = new EditGameForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                foreach ($request->get('CreateProductForm') as $packageKey => $packageData) {
                    # code...
                }
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['game/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }

        $newPackageForm = new CreateProductForm();
        $game = $model->getGame();
        $products = $game->products;
        $editPackageForms = [];
        // foreach ($products as $product) {
        //     $editPackageForm = new EditProductForm();
            
        // }

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['game/index'])),
            'newPackageForm' => $newPackageForm
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeleteGameForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', 'Success!');
        $ref = $request->get('ref', Url::to(['game/index']));
        return $this->redirect($ref);
    }
    
}
