<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;

class OrderMailBehavior extends AttributeBehavior
{
	/**
	 * Send mail 
	 */
    public function send($template, $title, $params = [])
    {
        $order = $this->owner; // the model which attached this behavior
        $settings = Yii::$app->settings;
        $from = $settings->get('ApplicationSettingForm', 'admin_email', null);
        $fromName = sprintf("%s Administrator", Yii::$app->name);
		if (!$from) return;
        $to = $order->customer_email;
        try {
            return Yii::$app->mailer->compose($template, array_merge(['order' => $order], $params))
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
