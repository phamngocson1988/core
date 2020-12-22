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
use frontend\models\Operator;
use frontend\models\Bonus;
use frontend\models\Complain;
use frontend\models\OperatorStaff;;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionIndex()
    {
        $newestOperators = Operator::find()->limit(10)->orderBy(['id' => SORT_DESC])->all();
        $lastestBonuses = Bonus::find()->limit(5)->orderBy(['id' => SORT_DESC])->all();
        $lastestComplains = Complain::find()->limit(8)->orderBy(['id' => SORT_DESC])->all();
        return $this->render('index', [
            'newestOperators' => $newestOperators,
            'lastestBonuses' => $lastestBonuses,
            'lastestComplains' => $lastestComplains,
        ]);
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $result = [
                'status' => true, 
                'user_id' => Yii::$app->user->id,
            ];
            $user = Yii::$app->user->getIdentity();
            $operatorId = $user->getOperatorIdByRole(OperatorStaff::ROLE_ADMIN);
            if ($operatorId) {
                $operator = Operator::findOne($operatorId);
                if ($operator) {
                    $result['next'] = Url::to(['manage-operator/index', 'operator_id' => $operator->id, 'slug' => $operator->slug]);
                }
            }
            return json_encode($result);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

    public function actionSignup()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);
        $model = new \frontend\forms\SignupForm();

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

    public function actionActivate()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $key = $request->get('key');
        $confirmForm = new \frontend\forms\ActiveUserForm([
            'id'=>$id,
            'auth_key'=>$key,
        ]);

        if ($confirmForm->validate() && $user = $confirmForm->confirm()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'activate_account_success'));
            Yii::$app->getUser()->login($user);
        } else{
            Yii::$app->getSession()->setFlash('warning',Yii::t('app', 'activate_account_fail'));
        }
        
        return $this->goHome();
    }


    public function actionRequestPasswordReset()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);
        $model = new \frontend\forms\PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return json_encode(['status' => true, 'message' => Yii::t('app', 'an_email_was_sent_to_reset_password')]);
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
            $model = new \frontend\forms\ResetPasswordForm($token);
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

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionLanguage($language)
    {
        $language = in_array($language, array_keys(Yii::$app->params['languages'])) ? $language : 'en-US';
        Yii::$app->session->set('language', $language);
        return $this->asJson(['result' => true, 'language' => Yii::$app->session->get('language')]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionAdvertise()
    {
        return $this->render('advertise');
    }
    public function actionCorporate()
    {
        return $this->render('corporate');
    }
    public function actionContact()
    {
        return $this->render('contact');
    }

}
