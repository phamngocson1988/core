<?php
namespace website\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;

class ProfileController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     *
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        $model = new \website\forms\EditProfileForm();
        if ($model->load($request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData();
        }

        return $this->render('index', [
            'model' => $model,
            'passwordModel' => new \website\forms\ChangePasswordForm()
        ]);
    }


    public function actionPassword()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $model = new \website\forms\ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->change()) {
                return json_encode(['status' => true]);
            } else {
                return json_encode(['status' => false, 'errors' => sprintf('Sorry, we cannot send an SMS to %s', $model->phone)]);
            }
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    // public function actionChangeAvatar()
    // {
    //     $request = Yii::$app->request;
    //     if (!$request->isAjax) {
    //         return;
    //     }

    //     $imageId = $request->post('image_id');
    //     $model = new ChangeAvatarForm([
    //         'image_id' => $imageId
    //     ]);
        
    //     return $this->renderJson($model->change(), [], $model->getErrors());
    // }

    public function actionRequestSmsCode()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $phone = $request->post('phone');

        if (!$phone) {
            return $this->asJson(['status' => false, 'errors' => 'Phone number is required.']);
        }
        $model = new \website\forms\VerifyPhoneForm();
        $result = $model->send($phone);
        return $this->asJson(['status' => $result, 'errors' => 'An error is occurred.']);
    }

    public function actionVerifySmsCode()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $model = new \website\forms\VerifyPhoneForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->verify()) {
                return json_encode(['status' => true]);
            }
        }
        $message = $model->getErrorSummary(true);
        $message = reset($message);
        return json_encode(['status' => false, 'errors' => $message]);
    }

}
