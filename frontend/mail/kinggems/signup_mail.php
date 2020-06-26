<?php
use yii\helpers\Url;
$link = Url::to(['site/activate', 'id' => $user->id, 'key' => $user->auth_key], true);
?>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Dear <?=$user->name;?>,</P>
    <p>To activate your Kinggems Account, please verify your email address.</p>
  </td>
</tr>
<tr>
  <td align="center" style="padding-top:0px;padding-bottom:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="40%">
      <tbody>
        <tr>
          <td align="center" dir="ltr">
            <center><a href="<?=$link;?>" style="background:#E95D2B;border-radius:3px;color:#ffffff;display:block;font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;letter-spacing:1px;padding:12px 8px;text-decoration:none;font-size:12px!important;" target="_blank">
              Confirm your email.
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
  	<hr/>
    <P>Or, copy and paste the following URL into your browser:</P>
    <p><a href='<?=$link;?>' target='_blank'><?=$link;?></a></p>
  </td>
</tr>