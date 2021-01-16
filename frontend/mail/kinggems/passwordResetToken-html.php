<?php
use yii\helpers\Html;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Dear <?= Html::encode($user->name) ?>,</P>
    <p>Your password has reseted by email <?= Html::encode($user->email) ?> on <?= date(DATE_RFC2822);?></p>
    <hr>
    <p>Click to this link to change your password:</p>
  </td>
</tr>
<tr>
  <td align="center" style="padding-top:0px;padding-bottom:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="40%">
      <tbody>
        <tr>
          <td align="center" dir="ltr">
            <center><a href="<?=$resetLink;?>" style="background:#E95D2B;border-radius:3px;color:#ffffff;display:block;font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;letter-spacing:1px;padding:12px 8px;text-decoration:none;font-size:12px!important;" target="_blank">
              RESET LINK
              </a>
            </center>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <hr>
    <p>Did you not request this change? If you do not require a new password, let us know.</p>
    <p>If you did this, you can safely ignore this email. If you have not done this, please protect your account. Sincerely thank you! Kinggems security team.</p>
  </td>
</tr>