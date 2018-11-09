<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchGameForm;
use backend\forms\CreateGameForm;
use backend\forms\EditGameForm;
use backend\forms\DeleteGameForm;
use yii\data\Pagination;
use yii\helpers\Url;
use common\models\Game;
use backend\forms\CreateProductForm;
use backend\forms\EditProductForm;

class GameController extends Controller
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
     * Show the list of games
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status');
        $form = new FetchGameForm(['q' => $q, 'status' => $status]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
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
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $model = new CreateGameForm();
        $packages = [new CreateProductForm];
        if ($model->load(Yii::$app->request->post())) {
            if ($game = $model->save()) {
                $packages = [];
                $message = "Game is saved successfully.";
                $packageData = $request->post('packages', []);
                foreach ($packageData as $package) {
                    $packageModel = new CreateProductForm($package);
                    $packageModel->game_id = $game->id;
                    if (!$packageModel->save()) {
                        $message = "Package is not saved";
                    }
                    $packages[] = $packageModel;
                }
                Yii::$app->session->setFlash('success', $message);
                $ref = $request->get('ref', Url::to(['game/index']));
                return $this->redirect($ref);
            }
        }

        $this->view->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerCssFile('@web/vendor/assets/global/plugins/fancybox/source/jquery.fancybox.css', ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
        $this->view->registerJsFile('@web/vendor/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        return $this->render('create.tpl', [
            'model' => $model,
            'packages' => $packages,
            'back' => $request->get('ref', Url::to(['game/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $model = new EditGameForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['game/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
            $game = $model->getGame();
            $editProductForms = [];
            foreach ($game->products as $product) {
                $form = new EditProductForm();
                $form->setProduct($product);
                $form->loadData($product->id);
                $editProductForms[] = $form;
            }
            $newProductForm = new CreateProductForm();
        }



        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['game/index'])),
            'editProductForms' => $editProductForms,
            'newProductForm' => $newProductForm
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
