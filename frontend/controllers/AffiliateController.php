<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * AffiliateController
 */
class AffiliateController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->getIdentity();
        $request = Yii::$app->request;
        if ($request->isPost) {
            $affiliates = $request->post('affiliates', []);
            $setting = Yii::$app->settings;
            $mailer = Yii::$app->mailer;
            $admin =  $setting->get('ApplicationSettingForm', 'admin_email', null);

            $affiliates = array_filter($affiliates, function($affiliate) {
                $name = ArrayHelper::getValue($affiliate, 'name');
                if (!$name) return false;
                $email = ArrayHelper::getValue($affiliate, 'email');
                if (!$email) return false;
                $validator = new \yii\validators\EmailValidator();
                return $validator->validate($email);
            });
            foreach ($affiliates as $affiliate) {
                $name = ArrayHelper::getValue($affiliate, 'name');
                $email = ArrayHelper::getValue($affiliate, 'email');
                $count = UserRefer::find()->where(['user_id' => $user->id, 'email' => $email])->count();
                if (!$count) {
                    // Sent mail
                    $link = Url::to(['site/signup', 'affiliate' => $user->affiliate_code], true);
                    $mailer->compose('affiliate_mail', ['user' => $user, 'link' => $link])
                    ->setTo($email)
                    ->setFrom([$admin => Yii::$app->name])
                    ->setSubject(sprintf("[%s][Affiliate Email] You have received affiliate link from %s ", Yii::$app->name, $user->name))
                    ->setTextBody(sprintf("%s have just sent you a link: %s", $user->name, $link))
                    ->send();
                }
            }
        }
        return $this->render('index', [
            'user' => $user
        ]);
    }

    public function actionRequest()
    {
        $user = Yii::$app->user->getIdentity();
        if ($user->affiliate_code) return $this->redirect(['affiliate/index']);

    }
}