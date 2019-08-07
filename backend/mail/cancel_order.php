"Sub: KINGGEMS.US - Confirmed order cancelation 1702877373
<p>Dear Thu Chu,</p>
<p>Thank you for your shopping at Kinggems.us!</p>
<p>We would like to announce your order <?=$order->id;?> has been canceled. Details are as follows:</p>
<h3>Information Order</h3>
<ul>
<li>Order code: <?=$order->id;?></li>
<li>Order time: <?=date('F j, Y, g:i a', strtotime($order->created_at));?></li>
<li>Order status: Cancel</li>
</ul>
<h3>Order detail:</h3>
<table style="width: 50%; border: solid 1px #CCC">
	<tr>
		<th>GAME</th>
		<th>QUANTITY</th>
		<th>REASON</th>
	</tr>
	<tr>
		<td><?=$order->game_title;?></td>
		<td><?=number_format($order->quantity);?></td>
		<td></td>
	</tr>
</table>

<p>Any questions and suggestions, please contact via email:<?=Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');?> or Whatsaap, Wechat, Telegram by Phone number +8497 999 7559 (From 08:00 - 21:00 both Sat, Sun).</p>
<br/>
<p>This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</p>