<?php
use yii\helpers\Url;
?>
<p style="margin: 4px 0 10px;">Dear <?=$order->customer_name;?>,</p>
<p style="margin: 4px 0 10px;">Kinggems.us has refunded you with the order # <?=$order->id;?></p>
<p style="margin: 4px 0 10px;">Refund amount: $<?=number_format($order->total_price);?></p>
<p style="margin: 4px 0 10px;">This amount will be transferred to e-wallet</p>
<p style="margin: 4px 0 10px;">Reason: Buyer's favor</p>
<p style="margin: 4px 0 10px;">Refund method: Wallet transfer</p>
<p style="margin: 4px 0 10px;">Completion date / time: <?=date(DATE_RFC2822);?></p>
<p style="margin: 4px 0 10px;">Kinggems.us regreted that you have not experienced well with this transaction. Hope to serve you soon in the next order.</p>