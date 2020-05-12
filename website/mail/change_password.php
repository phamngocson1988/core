<?php
use yii\helpers\Html;
?>
<!-- "Subject: [Kinggems.us]- Changed password Successfully -->
<p style="margin: 4px 0 10px;">Dear <?= Html::encode($user->name) ?>,</p>
<p style="margin: 4px 0 10px;">Your password has been changed successfully on <?= date(DATE_RFC2822);?>.</p>
<p style="margin: 4px 0 10px;">If it was you, just ignore this email. If you have not done this, please contact us to protect your account. </p>
<!-- 
Operating system: Windows
Browers: Chrome
IP: 14.161.25.63
City: Ho Chi Minh, Vietnam -->