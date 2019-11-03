<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;

class PaymentTransactionMailBehavior extends AttributeBehavior
{
	/**
	 * Send mail 
	 */
    public function send($template, $title, $params = [])
    {
        $model = $this->owner; // the model which attached this behavior
        $settings = Yii::$app->settings;
        $from = $settings->get('ApplicationSettingForm', 'admin_email', null);
        $fromName = sprintf("%s Administrator", Yii::$app->name);
		if (!$from) return;
        $to = $model->user->email;
        try {
            return Yii::$app->mailer->compose($template, array_merge(['model' => $model], $params))
            ->setTo($to)
            ->setFrom([$from => $fromName])
            ->setSubject($title)
            ->setTextBody($title)
            ->send();
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
