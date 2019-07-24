<?php
namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use frontend\models\Promotion;

class PromotionController extends Controller
{
    public function actionIndex()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'promotion.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $command = Promotion::find();
        $models = $command->all();
        return $this->render('index', [
            'models' => $models,
            'q' => $q
        ]);
    }

    public function actionView($id)
    {
        $this->view->params['main_menu_active'] = 'promotion.index';
        $model = Promotion::findOne($id);
        if (!$model) throw new NotFoundHttpException("Can not find this promotion", 1);
        return $this->render('view', ['model' => $model]);
    }
}
?>