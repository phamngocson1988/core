<?php
use yii\helpers\Url;
$setting = Yii::$app->settings;
?>
<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">CONFIRMED YOUR ORDERS</h1>
<p style="margin: 4px 0 10px;">Order code: <?=$order->id;?></p>
<p style="margin: 4px 0 10px;">Dear <?=$order->customer->name;?>, </p>
<p style="margin: 4px 0 10px;">We would like to inform that your order #<?=$order->id;?> has been completed partially (Due to technical issue) as the details, below</p>
<ul>
    <li>Game: <?=$order->game_title;?></li>
    <li>Loaded amount: <strong><?=sprintf("%s / %s", number_format($new_unit), number_format($old_unit));?></strong></li>
    <li>Lack of amounts: <?=number_format((int)$old_unit - (int)$new_unit);?></li>
</ul>
<p style="margin: 4px 0 10px;">The rest of payment has been credited into your Kcoin wallet: <strong><?= number_format($refund_coin, 1);?> Kcoins</strong></p>
<p style="margin: 4px 0 10px;">Please login into game and check your character information (click <a href="<?=$order_link;?>">here</a> to find the attached Before/Aftere Image File for detailed information)</p>
<p style="margin: 4px 0 10px;">We hope you will be satisfied with the shopping experience at Kinggems! </p>
<hr/>
<p style="margin: 4px 0 10px;">This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message."</p>