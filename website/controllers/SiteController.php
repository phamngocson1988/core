<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;

// forms
use website\forms\FetchGameForm;
use website\forms\LoginForm;
use website\forms\SignupForm;
use website\forms\PasswordResetRequestForm;
use website\forms\ResetPasswordForm;
use website\forms\AskEmailRequestForm;

// models
use website\models\Game;
use website\models\HotNew;

// events
use website\events\SignupEventHandler;


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
                'rules' => [
                    [
                        'actions' => ['index', 'auth', 'error', 'test', 'social', 'test-mail'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['signup', 'login', 'request-password-reset', 'reset-password', 'request-email-reset'],
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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'site.index';
        $this->layout = 'home';

        // Hot deal
        $hotCommand = new FetchGameForm(['hot_deal' => Game::HOT_DEAL]);
        $hotGames = $hotCommand->getCommand()->orderBy(['updated_at' => SORT_DESC])->limit(5)->all();
        $trendCommand = new FetchGameForm(['new_trending' => Game::NEW_TRENDING]);
        $trendGames = $trendCommand->getCommand()->orderBy(['updated_at' => SORT_DESC])->limit(2)->all();
        $grossingCommand = new FetchGameForm(['top_grossing' => Game::TOP_GROSSING]);
        $grossingGames = $grossingCommand->getCommand()->orderBy(['updated_at' => SORT_DESC])->limit(2)->all();

        // hotnew
        $hotnews = HotNew::find()->limit(5)->orderBy(['id' => SORT_DESC])->all();

        return $this->render('index', [
            'hotGames' => $hotGames,
            'trendGames' => $trendGames,
            'grossingGames' => $grossingGames,
            'hotnews' => $hotnews,
        ]);
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);

        $scenario = $request->post('scenario');
        $model = new LoginForm(['scenario' => $scenario]);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

    public function actionSignup()
    {
        $request = Yii::$app->request;
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);
        if (!$request->isAjax) {
            if ($request->get('refer')) {
                Yii::$app->session->set('refer', $request->get('refer'));
            }
            if ($request->get('affiliate')) {
                Yii::$app->session->set('affiliate', $request->get('affiliate'));
            }
            return $this->redirect(['site/index', '#' => 'modalLogin']);
        }
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $model = new SignupForm();

        // Register an event
        if (Yii::$app->session->has('refer')) {
            // $referTitle = Html::encode("WELCOME TO KINGGEMS.US");
            // $referContent = Html::encode("You're invited to join our Kinggems.us- a top-up game service website. Let join us to check out hundreds of amazing mobile games and many surprising promotions. Enjoy your games and get a lot of bonus, WHY NOT!!! >>> Click here");
            // $this->view->registerMetaTag(['property' => 'og:title', 'content' => $referTitle], 'og:title');
            // $this->view->registerMetaTag(['property' => 'og:description', 'content' => $referContent], 'og:description');
            $model->refer = Yii::$app->session->get('refer');
            $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'referCheckingEvent']);
        }
        if (Yii::$app->session->has('affiliate')) {
            // $affTitle = Html::encode("WELCOME TO KINGGEMS.US");
            // $affContent = Html::encode("You're invited to join our Kinggems.us- a top-up game service website. Let join us to check out hundreds of amazing mobile games and many surprising promotions. Enjoy your games and get a lot of bonus, WHY NOT!!! >>> Click here");
            // $this->view->registerMetaTag(['property' => 'og:title', 'content' => $affTitle], 'og:title');
            // $this->view->registerMetaTag(['property' => 'og:description', 'content' => $affContent], 'og:description');
            $model->affiliate = Yii::$app->session->get('affiliate');
            $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'affiliateCheckingEvent']);
        }
        $model->on(SignupForm::EVENT_AFTER_SIGNUP, [SignupEventHandler::className(), 'notifyStaff']);

        if ($model->load($request->post()) && $model->validate()) {
            $user = $model->signup();
            if ($user) {
                Yii::$app->user->login($user);
                return json_encode(['status' => true]);
            } else {
                return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => 'There is something wrong. Please contact our staff.']);
            }
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

    public function actionRequestPasswordReset()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return json_encode(['status' => true]);
            } else {
                return json_encode(['status' => false, 'errors' => 'Sorry, we are unable to reset password for the provided email address.']);
            }
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

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

    public function actionRequestEmailReset()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);
        $model = new AskEmailRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->send()) {
                return json_encode(['status' => true]);
            } else {
                return json_encode(['status' => false, 'errors' => sprintf('Sorry, we cannot send an SMS to %s', $model->phone)]);
            }
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
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

    public function onAuthSuccess($client)
    {
        (new \website\components\auth\AuthHandler($client))->handle();
    }

    public function actionTest()
    {
        $request = Yii::$app->request;
        $username = $request->get('username');
        $currentUser = Yii::$app->user->getIdentity();
        if (Yii::$app->user->can('admin')) {
            echo 'admin';
            Yii::$app->user->logout();
            $newUser = \website\models\User::findByUsername($username);
            if ($newUser) {
                Yii::$app->user->login($newUser, 3600 * 24 * 30);
                echo $username;
            }
        }
        die('end');
    }

    public function actionSocial()
    {
        return $this->render('social');
    }

    public function actionTestMail()
    {
        $o = \website\models\Order::findOne(16883597);
        $supplier = $o->workingSupplier;
        $o->pushNotification(\website\components\notifications\OrderNotification::NOTIFY_SUPPLIER_CANCEL_ORDER, $supplier->supplier_id);
        // $user = \website\models\User::findOne($supplier->supplier_id);
        // $toEmail = $user->email;
        $o->log(sprintf("log something"));

        //
        // $user = \website\models\User::findOne($supplier->supplier_id);
        // $supplierMailer = Yii::$app->supplier_mailer;
        // $message = $supplierMailer->compose('test_mail', [
        //     'user' => $user,
        //     'order' => $o,
        // ]);

        // $message->setFrom('napgamehoanggia@gmail.com');
        // $message->setTo('phamngocson1988@gmail.com');
        // $message->setSubject('Test');
        // $message->send();

        // print_r($supplier->supplier_id);
        // print_r($o);
        die('send');
    }

}
