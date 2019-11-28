<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\verification\twilio\Sms;
use Twilio\Rest\Client;
use yii\helpers\ArrayHelper;

class TestController extends Controller
{
	public $layout = 'test';
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

    public function actionVerifyPhone()
    {
        $this->view->registerJsFile("https://sdk.accountkit.com/en_US/sdk.js", ['position' => \yii\web\View::POS_HEAD]);
        // Initialize variables
		$app_id = '734107406647333';
		$secret = 'c662746780d36a5440b104db49d976b7';
		$version = 'v1.0'; // 'v1.1' for example
        return $this->render('verify-phone', []);
    }

    public function actionAccountKit()
    {
    	return $this->render('account-kit', []);
    }

    public function actionPaypal()
    {
        $settings = Yii::$app->settings;
        $clientId = $settings->get('PaypalSettingForm', 'sandbox_client_id');

        $this->view->registerJsFile("https://www.paypal.com/sdk/js?client-id=$clientId&disable-card=visa,mastercard,amex,discover,jcb,elo,hiper", ['position' => \yii\web\View::POS_HEAD]);
        return $this->render('paypal', []);
    }

    public function actionPaypalCapture()
    {
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $data = $request->post();
            $status = ArrayHelper::getValue($data, 'status');
            // Payer information
            $payer = ArrayHelper::getValue($data, 'payer', []);
            $payer_email_address = ArrayHelper::getValue($payer, 'email_address');

            // purchase information
            $purchase_units = ArrayHelper::getValue($data, 'purchase_units', []);
            $purchase_unit = reset($purchase_units);

            // payment information
            $payments = ArrayHelper::getValue($purchase_unit, 'payments', []);
            $captures = ArrayHelper::getValue($payments, 'captures', []);
            $capture = reset($captures);
            $captureId = ArrayHelper::getValue($capture, 'id');

            if (strtoupper($status) != "COMPLETED") return $this->asJson(['status' => false]);

            $settings = Yii::$app->settings;
            $from = $settings->get('ApplicationSettingForm', 'customer_service_email', null);
            $fromName = sprintf("%s Administrator", Yii::$app->name);
            if ($from) {
                $payer_email_address = Yii::$app->user->identity->email;
                Yii::$app->mailer->compose('paypal_confirm_mail', ['data' => $data])
                ->setTo($payer_email_address)
                ->setFrom([$from => $fromName])
                ->setSubject(sprintf("AGREEMENT CONFIRMATION - %s", $captureId))
                ->setTextBody(sprintf("AGREEMENT CONFIRMATION - %s", $captureId))
                ->send();
            }
        }

        return $this->asJson([
            'status' => true, 
            'post' => $request->post(),
            'referer' => Yii::$app->request->referrer
        ]);
    }
}