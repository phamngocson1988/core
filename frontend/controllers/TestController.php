<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex() 
    {
        $this->view->registerJsFile('js/google_pay.js', ['depends' => ['\frontend\assets\AppAsset']]);
        $this->view->registerJsFile("https://pay.google.com/gp/p/js/pay.js", ['depends' => ['\yii\web\JqueryAsset'], "onload" => "onGooglePayLoaded()", "async" => "async"]);
        
        return $this->render('index');
    }
}