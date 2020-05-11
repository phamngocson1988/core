<?php
use yii\helpers\Url;
$setting = Yii::$app->settings;
?>
<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">NOTIFICATION</h1>
<p style="margin: 4px 0 10px;">Order code: <?=$order->id;?></p>
<p style="margin: 4px 0 10px;">Dear <?=$order->customer->name;?>, </p>
<p style="margin: 4px 0 10px;">You have been received new notification for order <?=$order->id;?>, the content below:</p>
<ul>
    <li>Game: <?=$order->game_title;?></li>
    <li>Quantity/ loaded amount: <?=number_format($order->total_unit);?></li>
    <li>Character name: <?=$order->character_name;?></li>
    <li>ID/ Username: <?=$order->username;?></li>
    <li>Server: <?=$order->server;?></li>
    <li>Login Method: <?=$order->login_method;?></li>
</ul>
<p style="margin: 4px 0 10px;"><strong style="color: blue"><?=strip_tags($content);?></strong></p>
<p style="margin: 4px 0 10px;">Please login and check your order information (click <a href="<?=$order_link;?>">here</a>), then find the "REPORT" button for detailed information</p>
<p style="margin: 4px 0 10px;">We hope you will be satisfied with the shopping experience at Kinggems! </p>
<hr/>
<p style="margin: 4px 0 10px;">This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message."</p>