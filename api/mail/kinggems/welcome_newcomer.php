<?php
use yii\helpers\Url;
?>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Dear <?=$user->name;?>,</P>
    <p>Thank you for your creating a Kinggems Account.</p>
    <p>* How to <a href='<?=Url::to(['site/question'], true);?>' target='_blank' style='text-decoration: none; color: #ffc107'>top up money?</a></p>
    <p>* How to <a href='<?=Url::to(['site/question'], true);?>' target='_blank' style='text-decoration: none; color: #ffc107'>order / buy gems?</a></p>
    <p>We hope you enjoy your new account!</p>
	<p>The Kinggems community team,</p>
  </td>
</tr>
