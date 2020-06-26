<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Dear <?=$order->customer->name;?>,</P>
    <p>We would like to inform that your order <span style="color:#E95D2B">#<?=$order->id;?> :</span> has been completed partially (Due to technical issue) as the details, below</p>
    + Game: <?=$order->game_title;?><br/>
    + Loaded amount: <strong><?=sprintf("%s / %s", number_format($new_unit), number_format($old_unit));?></strong><br/>
    + Lack of amounts: <?=number_format((int)$old_unit - (int)$new_unit);?><br/>
    <p>The rest of payment has been credited into your Kcoin wallet: <strong><?= number_format($refund_coin, 1);?> Kcoins</strong></p>
	<p>Please login into game and check your character information (click <a href="<?=$order_link;?>">here</a> to find the attached Before/Aftere Image File for detailed information)</p>
	<p>We hope you will be satisfied with the shopping experience at Kinggems! </p>
	<hr/>
	<p>This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message."</p>
  </td>
</tr>