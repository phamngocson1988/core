<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex() 
    {
        $settings = Yii::$app->settings;
        $adminEmail =  $settings->get('ApplicationSettingForm', 'admin_email', null);
        $contactEmail =  $settings->get('ApplicationSettingForm', 'contact_email', null);
        echo $contactEmail;
        die($adminEmail);
        return $this->render('index');
    }
}