<?php
use yii\helpers\Html;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<p style="margin: 4px 0 10px;">Dear <?= Html::encode($user->name) ?>,</p>
<p style="margin: 4px 0 10px;">Your password has reseted by email <?= Html::encode($user->email) ?> on <?= date(DATE_RFC2822);?><!--Thur, 02 May, 2019 at 09:27 (UTC +07)-->.</p>
<!-- <p style="margin: 4px 0 10px;">Operating system: Windows</p>
<p style="margin: 4px 0 10px;">Browers: Chrome</p>
<p style="margin: 4px 0 10px;">IP: 14.161.25.63</p>
<p style="margin: 4px 0 10px;">City: Ho Chi Minh, Vietnam</p> -->
<p style="margin: 4px 0 10px;">Click to this link to change your password: you can directly change your password:</p>
<div style="margin:auto">
    <a href="<?=$resetLink;?>" style="display:inline-block;text-decoration:none;background-color:#ffc107!important;margin-right:30px;text-align:center;border-radius:3px;color:#fff;padding:5px 10px;font-size:12px;font-weight:bold;margin-left:35%;margin-top:5px" target="_blank">RESET LINK</a>
</div>
<p style="margin: 4px 0 10px;">Did you not request this change? If you do not require a new password, let us know.</p>
<p style="margin: 4px 0 10px;">If you did this, you can safely ignore this email. If you have not done this, please protect your account. Sincerely thank you! Kinggems security team.</p>