<p style="margin: 4px 0 10px;">Dear <?=$order->customer_name;?>,</p>
<p style="margin: 4px 0 10px;">Have a nice day!</p>
<p style="margin: 4px 0 10px;">We would like to announce your order <?=$order->id;?> has been canceled. Details are as follows:</p>
<h3>Order information: </h3>
<ul>
    <li>Order code: <?=$order->id;?></li>
    <li>Date: <?=$order->created_at;?></li>
    <li>Game name: <?=$order->game_title;?></li>
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