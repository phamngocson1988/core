<?php
$setting = Yii::$app->settings;
?>
<img src="<?=$setting->get('ApplicationSettingForm', 'logo', 'https://kinggems.us/images/logo.png');?>" target="_blank"/>
<h1>VERIFY YOUR ORDERS</h1>
<p>Order code: <?=$order->id;?></p>
<p>Dear <?=$order->customer->name;?>, </p>
<p>Thank you for buying at www.kinggems.us !</p>
<p>Your order has accepted, We will deliver as soon as possible.</p>
<!-- <p>Estimated delivery time: ???????</p> -->
<h3>Order information: </h3>
<ul>
    <li>Order code: <?=$order->id;?></li>
    <li>Date: <?=$order->created_at;?></li>
    <li>Game name: <?=$order->game_title;?></li>
</ul>
<h3>Details: </h3>
<table style="width:100%" border="1">
<tr>
<th>Name Game</th>
<th>Quantity</th>
<th><?=$order->unit_name;?></th>
<th>Total Price</th>
</tr>
<tr>
<td><?=$order->game_title;?></td>
<td><?=number_format($order->quantity);?></td>
<td><?=number_format($order->total_unit);?></td>
<td><?=number_format($order->total_price);?></td>
</tr>
</table>
<p>You can check your order by click <a>here</a>.</p>
<p>We hope you will be satisfied with the shopping experience and selected products at Kinggems! See you again soon!</p>
<p>By placing your order, you agree to Kinggems.us’s Privacy Notice and Conditions of Use. Unless otherwise noted, items sold by Kinggems.us </p>
<hr/>
<p>This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message."</p>