<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use frontend\models\UserRefer;

/**
 * ReferController
 */
class ReferController extends Controller
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
        $this->view->params['body_class'] = 'global-bg';
        $user = Yii::$app->user->getIdentity();
        $request = Yii::$app->request;
        if ($request->isPost) {
            $refers = $request->post('refers', []);
            $setting = Yii::$app->settings;
            $mailer = Yii::$app->mailer;
            $admin =  $setting->get('ApplicationSettingForm', 'admin_email', null);

            $refers = array_filter($refers, function($refer) {
                $name = ArrayHelper::getValue($refer, 'name');
                if (!$name) return false;
                $email = ArrayHelper::getValue($refer, 'email');
                if (!$email) return false;
                $validator = new \yii\validators\EmailValidator();
                return $validator->validate($email);
            });
            foreach ($refers as $refer) {
                $name = ArrayHelper::getValue($refer, 'name');
                $email = ArrayHelper::getValue($refer, 'email');
                $count = UserRefer::find()->where(['user_id' => $user->id, 'email' => $email])->count();
                if (!$count) {
                    // Sent mail
                    $link = Url::to(['site/signup', 'refer' => $user->refer_code], true);
                    $mailer->compose('refer_mail', ['user' => $user, 'link' => $link])
                    ->setTo($email)
                    ->setFrom([$admin => Yii::$app->name])
                    ->setSubject(sprintf("[%s][Refer Email] You have received refer link from %s ", Yii::$app->name, $user->name))
                    ->setTextBody(sprintf("%s have just sent you a link: %s", $user->name, $link))
                    ->send();
                            
                    // Save model
                    $model = new UserRefer();
                    $model->user_id = $user->id;
                    $model->name = $name;
                    $model->email = $email;
                    $model->status = UserRefer::STATUS_SENT;
                    $model->save();
                }
            }
        }
        return $this->render('index', [
            'user' => $user
        ]);
    }

    public function actionHistory()
    {
        $command = UserRefer::find()->where(['user_id' => Yii::$app->user->id]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('history', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionReport()
    {
        $command = UserRefer::find()->where([
            'user_id' => Yii::$app->user->id,
            'status' => UserRefer::STATUS_COMPLETED
        ]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('report', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }
}