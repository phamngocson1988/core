<?php
use yii\helpers\Url;
?>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Dear <?=$wallet->user->name;?>,</P>
    <p>We are glad to inform that your deposit money has been creditted into your account on Kinggems.us. Kindly check the balance our website:</p>
    + The remainning balance: <?=$wallet->balance;?> Kcoins<br/>
    + Top up amount: <?=$wallet->coin;?> Kcoins<br/>
    + The last balance: <?=$wallet->balance - $wallet->coin;?> Kcoins<br/>
    <hr>
    <p>Please Click the button below to confirm your transaction</p>
  </td>
</tr>
<tr>
  <td align="center" style="padding-top:0px;padding-bottom:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="40%">
      <tbody>
        <tr>
          <td align="center" dir="ltr">
            <center><a href="<?=Url::to(['user/wallet'], true);?>" style="background:#E95D2B;border-radius:3px;color:#ffffff;display:block;font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;letter-spacing:1px;padding:12px 8px;text-decoration:none;font-size:12px!important;" target="_blank">
              CONFIRM
              </a>
            </center>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>