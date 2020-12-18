<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\SupplierGame;

class SupplierGameController extends Controller
{
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

	public function actionEnable() 
    {
    	$request = Yii::$app->request;
    	$supplier_id = $request->get('supplier_id');
    	$game_id = $request->get('game_id');
        $form = new \backend\forms\UpdateSupplierGameStatusForm([
        	'supplier_id' => $supplier_id,
            'game_id' => $game_id
        ]);
        if ($form->validate() && $form->enable()) {
        	return $this->asJson(['status' => true]);
        } else {
        	$errors = $form->getErrorSummary(false);
        	$error = reset($errors);
        	return $this->asJson(['status' => false, 'errors' => $error]);
        }
    }

    public function actionDisable() 
    {
        $request = Yii::$app->request;
    	$supplier_id = $request->get('supplier_id');
    	$game_id = $request->get('game_id');
        $form = new \backend\forms\UpdateSupplierGameStatusForm([
        	'supplier_id' => $supplier_id,
            'game_id' => $game_id
        ]);
        if ($form->validate() && $form->disable()) {
        	return $this->asJson(['status' => true]);
        } else {
        	$errors = $form->getErrorSummary(false);
        	$error = reset($errors);
        	return $this->asJson(['status' => false, 'errors' => $error]);
        }
    }

    public function actionDispatcher()
    {
        $request = Yii::$app->request;
        $game_id = $request->get('game_id');
        $supplier_id = $request->get('supplier_id');
        $action = $request->get('action');
        $form = new \backend\forms\SwitchDispatcherSupplierForm([
            'game_id' => $game_id,
            'supplier_id' => $supplier_id,
            'action' => $action,
        ]);
        if ($form->change()) {
            return $this->renderJson(true);
        } else {
            $errors = $form->getFirstErrors();
            return $this->renderJson(false, null, $errors);
        }
        return $this->redirectNotFound();
    }
}