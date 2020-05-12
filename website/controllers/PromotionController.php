<?php
namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use frontend\models\Promotion;
use yii\data\Pagination;

class PromotionController extends Controller
{
    public function actionIndex()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'promotion.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $cat = $request->get('cat');
        $command = Promotion::find();
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
        }
        if ($cat) {
            $command->andWhere(['category' => $cat]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'q' => $q,
            'cat' => $cat
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