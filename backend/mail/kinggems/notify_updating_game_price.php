<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>We would like to announce price of <?=$game->title;?> (ID: <?=$game->id;?>) has been changed:</P>
    <p>Details: </p>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#ffc107; margin: 20px 0">
	  <thead>
	    <tr>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Old price</th>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">New price</th>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Old reseller 1</th>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">New reseller 1</th>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Old reseller 2</th>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">New reseller 2</th>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Old reseller 3</th>
	      <th align="left" bgcolor="#ffc107" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">New reseller 3</th>
	    </tr>
	  </thead>
	  <tbody bgcolor="#eee" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
	    <tr>
	      <td align="left" valign="top" style="padding:3px 9px"><?=$changes['old_price'];?></td>
	      <td align="left" valign="top" style="padding:3px 9px"><?=sprintf("%s (%s)", $changes['new_price'], $changes['new_price'] - $changes['old_price']);?></td>
	      <td align="left" valign="top" style="padding:3px 9px"><?=$changes['old_reseller_1'];?></td>
	      <td align="left" valign="top" style="padding:3px 9px"><?=sprintf("%s (%s)", $changes['new_reseller_1'], $changes['new_reseller_1'] - $changes['old_reseller_1']);?></td>
	      <td align="left" valign="top" style="padding:3px 9px"><?=$changes['old_reseller_2'];?></td>
	      <td align="left" valign="top" style="padding:3px 9px"><?=sprintf("%s (%s)", $changes['new_reseller_2'], $changes['new_reseller_2'] - $changes['old_reseller_2']);?></td>
	      <td align="left" valign="top" style="padding:3px 9px"><?=$changes['old_reseller_3'];?></td>
	      <td align="left" valign="top" style="padding:3px 9px"><?=sprintf("%s (%s)", $changes['new_reseller_3'], $changes['new_reseller_3'] - $changes['old_reseller_3']);?></td>
	    </tr>
	  </tbody>
	</table>
  </td>
</tr>