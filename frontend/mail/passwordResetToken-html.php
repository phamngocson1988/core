<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Subject: Reset password Kinggems</p>
    Logo Kinggems
    <p></p>
    <p>Dear <?= Html::encode($user->name) ?>,</p>
    <p>Your password has reseted by email <?= Html::encode($user->email) ?> on <?= date('DATE_RFC2822');?><!--Thur, 02 May, 2019 at 09:27 (UTC +07)-->.</p>
    <p>Operating system: Windows</p>
    <p>Browers: Chrome</p>
    <p>IP: 14.161.25.63</p>
    <p>City: Ho Chi Minh, Vietnam</p>
    <p></p>
    <p>Click to this link to change your password: you can directly change your password: <?= Html::a('Reset password', $resetLink) ?> </p>
    <p>Did you not request this change? If you do not require a new password, let us know.</p>
    <p>If you did this, you can safely ignore this email. If you have not done this, please protect your account. Sincerely thank you! Kinggems security team.</p>
</div>