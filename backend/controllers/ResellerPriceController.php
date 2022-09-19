<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\CreateResellerPriceForm;
use backend\forms\DeleteResellerPriceForm;
use backend\forms\FetchResellerPriceForm;

class ResellerPriceController extends Controller
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
        $this->view->params['main_menu_active'] = 'reseller-price.index';
        $request = Yii::$app->request;
        $reseller_id = $request->get('reseller_id');
        $game_id = $request->get('game_id');
        $form = new FetchResellerPriceForm(['reseller_id' => $reseller_id, 'game_id' => $game_id]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();
        return $this->render('index.php', [
            'models' => $models, 
            'search' => $form,
            'pages' => $pages
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'reseller-price.index';
        $request = Yii::$app->request;
        $model = new CreateResellerPriceForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                return $this->redirect(Url::to(['reseller-price/index']));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete()
    {
        $request = Yii::$app->request;
        $reseller_id = $request->get('reseller_id');
        $game_id = $request->get('game_id');
        $form = new DeleteResellerPriceForm(['reseller_id' => $reseller_id, 'game_id' => $game_id]);
        if (!$form->run()) {
            Yii::$app->session->setFlash('error', $form->getErrorSummary(true));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
        return $this->redirect(Url::to(['reseller-price/index']));
    }
}
