<?php
namespace api\controllers;

use Yii;
use api\forms\FetchRealestateForm;
use yii\data\Pagination;

class RealestateController extends ActiveController
{
    public function actionIndex()
    {
        $request = Yii::$app->request;

        $form = new FetchRealestateForm();
        if (!$form->validate()) {
            return ['result' => false, 'errors' => $form->getErrors()];
        }
        $models = $form->fetch();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        return [
            'result' => true, 
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
        ];
    }
}
