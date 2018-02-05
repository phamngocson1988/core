<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchPromotionForm;
use backend\forms\CreatePromotionForm;
use backend\forms\EditPromotionForm;
use backend\forms\DeletePromotionForm;
use yii\data\Pagination;
use yii\helpers\Url;
use common\models\Promotion;
use backend\components\actions\SettingsAction;
use backend\forms\PromotionBannerSettingForm;

class PromotionController extends Controller
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'banner' => [
                'class' => SettingsAction::class,
                'modelClass' => PromotionBannerSettingForm::class,
                'view' => 'banner.tpl',
                'layoutParams' => ['main_menu_active' => 'promotion.banner']
            ],
        ];
    }

    /**
     * Show the list of promotions
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'promotion.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $type = $request->get('type');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $status = $request->get('status');

        $condition = compact('q', 'type', 'from_date', 'to_date', 'status');
        $form = new FetchPromotionForm($condition);

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
        $this->view->params['main_menu_active'] = 'promotion.index';
        $request = Yii::$app->request;
        $model = new CreatePromotionForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['promotion/index']));
                return $this->redirect($ref);
            }
        }
        $this->view->registerJsFile('@web/js/ckeditor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css', ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);

        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['promotion/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'post.promotion';
        $request = Yii::$app->request;
        $model = new EditPromotionForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['promotion/index']));
                return $this->redirect($ref);
            }
        } else {
            $model->loadData($id);
        }
        $this->view->registerJsFile('@web/js/ckeditor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerJsFile('@web/js/ckeditor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->view->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css', ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);

        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['promotion/index']))
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $form = new DeletePromotionForm(['id' => $id]);
        if (!$form->delete()) {
            Yii::$app->session->setFlash('error', $form->getErrors('id'));
        }
        Yii::$app->session->setFlash('success', 'Success!');
        $ref = $request->get('ref', Url::to(['promotion/index']));
        return $this->redirect($ref);
    }
}
