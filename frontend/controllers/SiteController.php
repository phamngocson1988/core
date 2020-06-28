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

use frontend\models\User;
use frontend\models\Game;
use frontend\models\Promotion;
use frontend\models\UserRefer;
use frontend\models\QuestionCategory;
use frontend\models\Question;
use frontend\events\SignupEventHandler;

// Test new solution
use frontend\forms\RegisterForm;
use frontend\events\RegisterEventHandler;
use frontend\components\verification\twilio\Sms;

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
                        'roles' => ['?', '@'],
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
        $this->view->params['main_menu_active'] = 'site.index';
        $this->layout = 'home';
        $games = Game::find()->where(['pin' => Game::PIN])->orderBy(['updated_at' => SORT_DESC])->limit(10)->all();
        // Fetch valid promotions which just apply for game
        $promotions = Promotion::find()->andWhere(['rule_name' => 'specified_games'])->all();

        return $this->render('index', [
            'games' => $games,
            'promotions' => $promotions
        ]);
    }

    public function actionSaler($code)
    {
        if ($code) Yii::$app->session->set('saler_code', $code);
        return $this->goHome();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->view->params['body_class'] = 'global-bg';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->login()) {
            return $this->redirect(Yii::$app->user->getReturnUrl(['site/index']));
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
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
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
    public function actionSignup()
    {
        if (!Yii::$app->user->getIsGuest()) return $this->redirect(['site/index']);
        $this->view->params['body_class'] = 'global-bg';
        $request = Yii::$app->request;
        $model = new SignupForm();

        // Register an event
        $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'salerCheckingEvent']);
        $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'assignRole']);
        $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'sendActivationEmail']);
        if ($request->get('refer')) {
            $referTitle = Html::encode("WELCOME TO KINGGEMS.US");
            $referContent = Html::encode("You're invited to join our Kinggems.us- a top-up game service website. Let join us to check out hundreds of amazing mobile games and many surprising promotions. Enjoy your games and get a lot of bonus, WHY NOT!!! >>> Click here");
            $this->view->registerMetaTag(['property' => 'og:title', 'content' => $referTitle], 'og:title');
            $this->view->registerMetaTag(['property' => 'og:description', 'content' => $referContent], 'og:description');
            $model->refer = $request->get('refer');
            $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'referCheckingEvent']);
        }
        if ($request->get('affiliate')) {
            $affTitle = Html::encode("WELCOME TO KINGGEMS.US");
            $affContent = Html::encode("You're invited to join our Kinggems.us- a top-up game service website. Let join us to check out hundreds of amazing mobile games and many surprising promotions. Enjoy your games and get a lot of bonus, WHY NOT!!! >>> Click here");
            $this->view->registerMetaTag(['property' => 'og:title', 'content' => $affTitle], 'og:title');
            $this->view->registerMetaTag(['property' => 'og:description', 'content' => $affContent], 'og:description');
            $model->affiliate = $request->get('affiliate');
            $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'affiliateCheckingEvent']);
        }
        if ($model->load($request->post()) && ($user = $model->signup())) {
            // $verify = VerifyAccountViaPhoneForm::findOne($user->id);
            // if (!$verify->send()) {
            //     Yii::$app->getSession()->setFlash('error', $verify->getErrorSummary(true));
            // } else {
            //     Yii::$app->getSession()->setFlash('success', 'A verification code is sent to your phone, type it to form below to active your account.');
            // }
            // return $this->redirect(['site/verify-phone', 'id' => $user->id]);
            return $this->redirect(['site/verify-email', 'id' => $user->id]);
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionVerifyPhone($id)
    {
        $request = Yii::$app->request;
        $model = VerifyAccountViaPhoneForm::findOne($id);
        if (!$model) throw new NotFoundHttpException("User #$id not found.");

        // Register an event
        if ($model->status == VerifyAccountViaPhoneForm::STATUS_INACTIVE) {
            $model->on(VerifyAccountViaPhoneForm::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'referApplyingEvent']);
            $model->on(VerifyAccountViaPhoneForm::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'notifyWelcomeEmail']);
            $model->on(VerifyAccountViaPhoneForm::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'signonBonus']);
        }

        if ($model->load($request->post()) && $model->verify()) {
            // Yii::$app->getSession()->setFlash('success', 'Your account is activated successfully');
            Yii::$app->user->login($model, 3600 * 24 * 30);
            Yii::$app->getSession()->setFlash('popup-welcome', true);
            return $this->redirect(['site/index']);
        } else { 
            Yii::$app->getSession()->setFlash('error', $model->getErrorSummary(false));
        }
        return $this->render('verify-phone', ['model' => $model]);
    }

    public function actionSendVerificationCode($id)
    {
        $user = User::findOne(['id' => $id, 'status' => User::STATUS_INACTIVE]);
        if (!$user) throw new NotFoundHttpException("User #$id not found.");
        $service = new \common\components\telecom\SpeedSms();
        $phone = $user->phone;
        if (!$service->sms($phone)) {
            Yii::$app->getSession()->setFlash('error', $service->getErrorSummary(false));
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

    public function actionVerifyEmail($id)
    {
        $request = Yii::$app->request;
        $model = User::findOne($id);
        if (!$model) throw new NotFoundHttpException("User #$id not found.");
        if ($model->status != User::STATUS_INACTIVE) throw new BadRequestHttpException("Your account was activated. You can not active it again.", 1);
        return $this->render('verify-email', ['user' => $model]);
        // $model = User::findOne($id);
        // if (!$model) throw new NotFoundHttpException("User #$id not found.");

        // // Register an event
        // if ($model->status == VerifyAccountViaPhoneForm::STATUS_INACTIVE) {
        //     $model->on(VerifyAccountViaPhoneForm::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'referApplyingEvent']);
        //     $model->on(VerifyAccountViaPhoneForm::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'notifyWelcomeEmail']);
        //     $model->on(VerifyAccountViaPhoneForm::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'signonBonus']);
        // }

        // if ($model->load($request->post()) && $model->verify()) {
        //     // Yii::$app->getSession()->setFlash('success', 'Your account is activated successfully');
        //     Yii::$app->user->login($model, 3600 * 24 * 30);
        //     Yii::$app->getSession()->setFlash('popup-welcome', true);
        //     return $this->redirect(['site/index']);
        // } else { 
        //     Yii::$app->getSession()->setFlash('error', $model->getErrorSummary(false));
        // }
        // return $this->render('verify-phone', ['model' => $model]);
    }

    public function actionActivate()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $key = $request->get('key');
        // $confirmForm = new ActiveCustomerForm([
        //     'id'=>$id,
        //     'auth_key'=>$key,
        // ]);

        $model = User::find()->where([
            'id' => $id,
            'auth_key' => $key,
        ])->one();
        if (!$model) throw new NotFoundHttpException("User #$id not found.");
        if ($model->status != User::STATUS_INACTIVE) throw new BadRequestHttpException("Your request is invalid", 1);
        $model->on(User::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'referApplyingEvent']);
        $model->on(User::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'notifyWelcomeEmail']);
        $model->on(User::EVENT_AFTER_UPDATE, [SignupEventHandler::className(), 'signonBonus']);
        $model->status = User::STATUS_ACTIVE;

        if ($model->save()) {
            Yii::$app->getSession()->setFlash('success','You have activated your account successfully!');
            Yii::$app->getUser()->login($model);
        } else{
            Yii::$app->getSession()->setFlash('warning','There is something wrong. Please contact with our customer service!');
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
        $this->view->params['body_class'] = 'global-bg';
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

    public function actionQuestion()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'site.question';
        $categories = QuestionCategory::find()->all();
        return $this->render('question', [
            'categories' => $categories,
        ]);
    }

    public function actionQuestionCategory($id, $slug)
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'site.question';
        $category = QuestionCategory::findOne($id);
        return $this->render('question-category', [
            'category' => $category,
        ]);
    }

    public function actionQuestionSearch()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'site.question';
        $q = Yii::$app->request->get('q');
        $command = Question::find();
        $command->where(['like', 'title', $q]);
        $command->orWhere(['like', 'content', $q]);
        return $this->render('question-search', [
            'models' => $command->all(),
            'q' => $q
        ]);
    }

    public function actionQuestionDetail($id, $slug) 
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'site.question';
        $question = Question::findOne($id);
        return $this->render('question-detail', [
            'question' => $question,
        ]);
    }

    public function actionTerm($slug)
    {
        $this->view->params['body_class'] = 'global-bg';
        $request = Yii::$app->request;
        $content = Yii::$app->settings->get('TermsConditionForm', $slug);
        if ($request->isAjax) {
            return $this->renderParital('term', ['content' => $content]);
        }
        return $this->render('term', ['content' => $content]);
    }

    public function actionQueue($id)
    {
        // $queue = new \console\queue\SignupEmail(['id' => Yii::$app->user->id]);
        $queue = new \console\queue\DeleteOrder(['id' => $id]);
        Yii::$app->queue->push($queue);
        echo '<pre>';
        print_r($queue);
        echo '</pre>';
        die;
    }

    public function actionEmail($email)
    {
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        Yii::$app->supplier_mailer->compose('test_mail')
            ->setTo($email)
            ->setFrom([$adminEmail => Yii::$app->name . ' Administrator'])
            ->setSubject(sprintf("TESTING EMAIL"))
            ->setTextBody("Thanks for your deposit")
            ->send();
        var_dump($email);die;
    }

    public function actionSms($phone)
    {
        if (!$phone) die('no phone');
        $result = Yii::$app->sms->compose()
            ->setTo("+" . $phone) //+8618579804779
            ->setMessage("Kinggems.us: send test sms from trail account")
            ->send();
        var_dump($result);
        die;
        
    }

    public function actionRegister()
    {
        $this->view->params['body_class'] = 'global-bg';
        $request = Yii::$app->request;
        $session = Yii::$app->session;

        $model = new RegisterForm();
        // Register metadata
        if ($request->get('refer')) {
            $referTitle = Html::encode("WELCOME TO KINGGEMS.US");
            $referContent = Html::encode("You're invited to join our Kinggems.us- a top-up game service website. Let join us to check out hundreds of amazing mobile games and many surprising promotions. Enjoy your games and get a lot of bonus, WHY NOT!!! >>> Click here");
            $this->view->registerMetaTag(['property' => 'og:title', 'content' => $referTitle], 'og:title');
            $this->view->registerMetaTag(['property' => 'og:description', 'content' => $referContent], 'og:description');
            $model->refer = $request->get('refer');
            $model->on(RegisterForm::EVENT_AFTER_SIGNUP, [RegisterEventHandler::className(), 'referCheckingEvent']);
        }
        if ($session->get('affiliate')) {
            $affTitle = Html::encode("WELCOME TO KINGGEMS.US");
            $affContent = Html::encode("You're invited to join our Kinggems.us- a top-up game service website. Let join us to check out hundreds of amazing mobile games and many surprising promotions. Enjoy your games and get a lot of bonus, WHY NOT!!! >>> Click here");
            $this->view->registerMetaTag(['property' => 'og:title', 'content' => $affTitle], 'og:title');
            $this->view->registerMetaTag(['property' => 'og:description', 'content' => $affContent], 'og:description');
            $model->affiliate = $session->get('affiliate');
            $model->on(RegisterForm::EVENT_AFTER_SIGNUP, [RegisterEventHandler::className(), 'affiliateCheckingEvent']);
        }

        $scenario = $request->post('scenario', RegisterForm::SCENARIO_VALIDATE);
        $service = new Sms(['testing_mode' => false]);
        $model->setVerifier($service);
        $model->setScenario($scenario);
        if ($model->load($request->post()) && $model->validate()) {
            if ($scenario == RegisterForm::SCENARIO_VALIDATE) {
                $session->set('user_phone', $model->phone);
                $model->sendVerification();
                return $this->render('register', ['model' => $model, 'scenario' => RegisterForm::SCENARIO_INFORMATION]);
            }
            if ($scenario == RegisterForm::SCENARIO_INFORMATION) {
                if ($model->verify()) {
                    $model->on(RegisterForm::EVENT_AFTER_SIGNUP, [RegisterEventHandler::className(), 'salerCheckingEvent']);
                    $model->on(RegisterForm::EVENT_AFTER_SIGNUP, [RegisterEventHandler::className(), 'assignRole']);
                    $model->on(RegisterForm::EVENT_AFTER_SIGNUP, [RegisterEventHandler::className(), 'referApplyingEvent']);
                    $model->on(RegisterForm::EVENT_AFTER_SIGNUP, [RegisterEventHandler::className(), 'notifyWelcomeEmail']);
                    $model->on(RegisterForm::EVENT_AFTER_SIGNUP, [RegisterEventHandler::className(), 'signonBonus']);
                    $model->phone = $session->get('user_phone');
                    $model->signup();
                    return $this->redirect(['site/index']);    
                }
                return $this->render('register', ['model' => $model, 'scenario' => RegisterForm::SCENARIO_INFORMATION]);
            }
        } else {
            if ($model->hasErrors('phone')) {
                Yii::$app->session->setFlash('error', $model->getErrors('phone'));
            }
        }
        return $this->render('register', ['model' => $model, 'scenario' => RegisterForm::SCENARIO_VALIDATE]);
    }

}
