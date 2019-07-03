<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;

use frontend\forms\LoginForm;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;
use frontend\forms\ActiveCustomerForm;
use frontend\forms\ContactForm;
use frontend\forms\VerifyAccountViaPhoneForm;

use common\models\User;
use common\forms\SendmailForm;
use frontend\models\Game;
use frontend\models\Promotion;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                    'ajax-login' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                // 'layout' => 'notice'
                'layout' => 'main'
            ],
            'captcha' => [
                'class' => '\frontend\components\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'home';
        $games = Game::find()->limit(10)->all();
        // Fetch valid promotions which just apply for game
        $promotions = Promotion::find()->andWhere(['rule_name' => 'specified_games'])->all();

        return $this->render('index', [
            'games' => $games,
            'promotions' => $promotions
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        // $this->layout = 'signup';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->login()) {
            // return $this->goBack();
            return $this->redirect($request->getReferrer());
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionAjaxLogin()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id]);
        } else {
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $model->getErrors()]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $settings = Yii::$app->settings;
            $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
            if (!$adminEmail) throw new BadRequestHttpException("Some error occured. Please contact to admin.", 1);
            
            if ($model->sendEmail($adminEmail)) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup1()
    {
        // $this->layout = 'signup';
        $model = new SignupForm();
        $model->setNeedConfirm(true);
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                // Send mail notify admin if this user register to become reseller
                if ($model->is_reseller == User::IS_RESELLER) {
                    $settings = Yii::$app->settings;
                    $adminEmail =  $settings->get('ApplicationSettingForm', 'admin_email', null);
                    if ($adminEmail) {
                        $notifyMail = new SendmailForm();
                        $notifyMail->subject = '[Notification] New reseller have just registered';
                        $notifyMail->body = 'New reseller have just registered: #' . $user->id;
                        $notifyMail->template = 'notify_reseller_mail';
                        $notifyMail->params = ['user' => $user];
                        $notifyMail->send($adminEmail);
                    }
                }
                // If need to confirm, send mail to customer
                if ($model->isNeedConfirm()) {
                    $activeLink = Yii::$app->urlManager->createAbsoluteUrl(['site/activate', 'id' => $user->id, 'key' => $user->auth_key]);
                    $signupMail = new SendmailForm();
                    $signupMail->subject = 'Signup Confirmation';
                    $signupMail->body = 'Click this link ' . Html::a('confirm', $activeLink);
                    $signupMail->template = 'signup_mail';
                    $signupMail->params = ['user' => $user, 'link' => $activeLink];
                    $signupMail->send($user->email);
                } else { // If no need to confirm, log user in
                    Yii::$app->getUser()->login($user);
                }                
                return $this->redirect(['site/success']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $request = Yii::$app->request;
        $model = new SignupForm();
        if ($model->load($request->post()) && ($user = $model->signup())) {
            $verify = VerifyAccountViaPhoneForm::findUserByAuth($user->auth_key);
            if (!$verify->send()) {
                Yii::$app->getSession()->setFlash('error', $verify->getErrorSummary(true));
            } else {
                Yii::$app->getSession()->setFlash('success', 'A verification code is sent to your phone, type it to form below to active your account.');
            }
            return $this->redirect(['site/verify-phone', 'auth' => $user->auth_key]);
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionVerifyPhone($auth)
    {
        $request = Yii::$app->request;
        $model = VerifyAccountViaPhoneForm::findUserByAuth($auth);
        if (!$model) throw new NotFoundHttpException('model does not exist.');
        if ($model->load($request->post()) && $model->verify()) {
            Yii::$app->getSession()->setFlash('success', 'Your account is activated successfully');
            return $this->redirect(['site/login']);
        } else {
            $model->send();
            print_r($model);
        }
        return $this->render('verify-phone', ['model' => $model]);
    }

    public function actionTest($phone)
    {
        $provider = new \common\components\telecom\SpeedSms();
        if (!$provider->sms($phone)) {
            print_r($provider);
        }
        echo 'Success';
    }

    public function actionSendVerificationCode($auth)
    {
        $user = User::findOne(['auth_key' => $auth, 'status' => User::STATUS_INACTIVE]);
        if (!$user) throw new NotFoundHttpException('model does not exist.');
        $service = new \common\components\telecom\SpeedSms();
        $phone = sprintf("%s%s", $user->country_code, $user->phone);
        if (!$service->sms($phone)) {
            Yii::$app->getSession()->setFlash('error', $service->getErrorSummary(true));
            return $this->redirect($request->getReferrer());
        }
        return $this->redirect(['site/verify-phone', 'auth' => $auth]);
    }

    public function actionFindEmail($email)
    {
        $user = User::findOne(['email' => $email]);
        $result = ($user instanceOf User);
        return $this->renderJson($result);
    }

    public function actionActivate()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $key = $request->get('key');
        $confirmForm = new ActiveCustomerForm([
            'id'=>$id,
            'auth_key'=>$key,
        ]);

        if ($user = $confirmForm->confirm()) {
            Yii::$app->getSession()->setFlash('success','Success!');
            Yii::$app->getUser()->login($user);
        } else{
            Yii::$app->getSession()->setFlash('warning','Failed!');
        }
        
        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'signup';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'signup';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSuccess()
    {
        $this->layout = 'notice';

        return $this->render('notice', [
            'title' => 'You have registered successfully.',
            'content' => 'Check your email for activation link'
        ]);
    }
}
