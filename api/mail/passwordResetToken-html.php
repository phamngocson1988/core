<?php
use yii\helpers\Html;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<p style="margin: 4px 0 10px;">Dear <?= Html::encode($user->name) ?>,</p>
<p style="margin: 4px 0 10px;">Kindly be informed that we have received the reset password request on <?= date(DATE_RFC2822);?><!--Thur, 02 May, 2019 at 09:27 (UTC +07)-->.</p>
<!-- <p style="margin: 4px 0 10px;">Operating system: Windows</p>
<p style="margin: 4px 0 10px;">Browers: Chrome</p>
<p style="margin: 4px 0 10px;">IP: 14.161.25.63</p>
<p style="margin: 4px 0 10px;">City: Ho Chi Minh, Vietnam</p> -->
<p style="margin: 4px 0 10px;">If that was you, please click to this link to reset your password:</p>
<div style="margin:auto">
    <a href="<?=$resetLink;?>" style="display:inline-block;text-decoration:none;background-color:#ffc107!important;margin-right:30px;text-align:center;border-radius:3px;color:#fff;padding:5px 10px;font-size:12px;font-weight:bold;margin-left:35%;margin-top:5px" target="_blank">RESET LINK</a>
</div>
<p style="margin: 4px 0 10px;">If that was NOT you, kindly ignore this email check futher to secure your account. Thank you!</p>