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

    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

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
            $username = $settings->get('PaypalSettingForm', 'username');
            $password = $settings->get('PaypalSettingForm', 'password');
            if ($username && $password) {
                Yii::error(sprintf("Mail send from %s to %s", $username, $payer_email_address), __METHOD__);
                $fromName = sprintf("%s Administrator", Yii::$app->name);
                $mailer = Yii::createObject([
                    'class' => 'yii\swiftmailer\Mailer',
                    'viewPath' => '@frontend/mail',
                    'transport' => [
                        'class' => 'Swift_SmtpTransport',
                        'host' => 'smtp.gmail.com',
                        'username' => $username,
                        'password' => $password,
                        'port' => '587',
                        'encryption' => 'tls',
                    ],            
                    'useFileTransport' => false,
                ]);
                $payer_email_address = Yii::$app->user->identity->email;
                $mailer->compose('paypal_confirm_mail', ['data' => $data])
                ->setTo($payer_email_address)
                ->setFrom([$username => $fromName])
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

    public function actionGoogle()
    {
        return $this->render('google');
    }

    public function onAuthSuccess($client)
    {
        print_r($client);

    }

    protected function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Gmail API PHP Quickstart');
        $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
        // $client->setAuthConfig('client_secret.json');
        $client->setClientId($config[$key]['client_id']);
        $client->setClientSecret($config[$key]['client_secret']);
        if (isset($config[$key]['redirect_uris'])) {
        $client->setRedirectUri($config[$key]['redirect_uris'][0]);
        }

        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public function actionPush()
    {
        return $this->render('push');
    }
}