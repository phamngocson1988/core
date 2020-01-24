<?php
use yii\helpers\Url;
?>
<p style="margin: 4px 0 10px;">Dear <?=$supplier->name;?>, </p>
<p style="margin: 4px 0 10px;">You have been received new request for processing order <?=$order->id;?>, the content below:</p>
<ul>
    <li>Game: <?=$order->game_title;?></li>
    <li>Quantity/ loaded amount: <?=number_format($order->total_unit);?></li>
    <li>Character name: <?=$order->character_name;?></li>
    <li>ID/ Username: <?=$order->username;?></li>
    <li>Server: <?=$order->server;?></li>
    <li>Login Method: <?=$order->login_method;?></li>
</ul>
<p style="margin: 4px 0 10px;">Please login and check your request information</p>
