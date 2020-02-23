<?php
use yii\helpers\Url;
$setting = Yii::$app->settings;
?>
<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">VERIFY YOUR ORDERS</h1>
<p style="margin: 4px 0 10px;">Dear <?=$order->customer->name;?>, </p>
<p style="margin: 4px 0 10px;">Thank you for shopping at www.kinggems.us !</p>
<p style="margin: 4px 0 10px;">Kindly be informed that your order has been accepted, It has been processed since now. We will inform you when It's done </p>
<p style="margin: 4px 0 10px;">That's our pleasure for supporting you! </p>
<h3>Order detail: </h3>
<ul>
    <li>Order no: <?=$order->id;?></li>
    <li>Time: <?=$order->created_at;?></li>
    <li>Estimated delivery time: 60-90mins </li>
</ul> 
<h3 style="font-size:13px;font-weight:bold;color:#ffc107;text-transform:uppercase;margin:20px 0 0 0;border-bottom:1px solid #ddd">Details: </h3>
<table cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#ffc107; margin: 20px 0">
  <thead>
    <tr>
      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Game</th>
      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
        <span class="il">Quantity</th>
      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px"><?=$order->unit_name;?></th>
      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Total Price</th>
    </tr>
  </thead>
  <tbody bgcolor="#eee" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
    <tr>
      <td align="left" valign="top" style="padding:3px 9px"><?=$order->game_title;?></td>
      <td align="left" valign="top" style="padding:3px 9px"><?=number_format($order->quantity);?></td>
      <td align="left" valign="top" style="padding:3px 9px"><?=number_format($order->total_unit);?></td>
      <td align="left" valign="top" style="padding:3px 9px"><?=number_format($order->total_price);?></td>
    </tr>
  </tbody>
</table>
<p style="margin: 4px 0 10px;">You can check your order by click <a href="<?=$order_link;?>" target="_blank" style='text-decoration: none; color: #ffc107'>here</a>.</p>
<p style="margin: 4px 0 10px;">We hope you will be satisfied with the shopping experience and selected products at Kinggems! See you again soon!</p>
<p style="margin: 4px 0 10px;">By placing your order, you agree to Kinggems.us’s Privacy Notice and Conditions of Use. Unless otherwise noted, items sold by Kinggems.us </p>
<hr/>
<p style="margin: 4px 0 10px;">This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message."</p>