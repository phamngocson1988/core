<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController
 */
class UserController extends Controller
{
	// public function behaviors()
 //    {
 //        return [
 //            'access' => [
 //                'class' => AccessControl::className(),
 //                'rules' => [
 //                    [
 //                        'roles' => ['?'],
 //                    ],
 //                ],
 //            ],
 //        ];
 //    }

    public function actionIndex()
    {
    	return $this->render('index');
    }
}