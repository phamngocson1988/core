<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\verification\twilio\Sms;
use Twilio\Rest\Client;

class TestController extends Controller
{
    public function actionIndex() 
    {
        $this->view->registerJsFile('js/google_pay.js', ['depends' => ['\frontend\assets\AppAsset']]);
        $this->view->registerJsFile("https://pay.google.com/gp/p/js/pay.js", ['depends' => ['\yii\web\JqueryAsset'], "onload" => "onGooglePayLoaded()", "async" => "async"]);
        
        return $this->render('index');
    }

    public function actionWhatsapp()
    {
    	$service = new Sms(['testing_mode' => false, 'useWhatsapp' => true]);
    	$result = $service->send("+84986803325", "Hello {pin}");
    	var_dump($result);
    	die;
    }

    public function actionTwilio()
    {
		// Your Account SID and Auth Token from twilio.com/console
		$account_sid = '';
		$auth_token = '';
		// In production, these should be environment variables. E.g.:
		// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

		// A Twilio number you own with SMS capabilities
		$twilio_number = "+19105503850";

		$client = new Client($account_sid, $auth_token);
		$message = $client->messages
		->create("whatsapp:+15005550006", // to
		       array(
		           "from" => "whatsapp:+14155238886",
		           "body" => "Hello there!"
		       )
		);

		print($message->sid);
    }
}