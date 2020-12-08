<?php
use yii\helpers\Url;
?>
<p style="margin: 4px 0 10px;">Dear <?=$user->name;?></p>
<p style="margin: 4px 0 10px;">Thank you for your creating a Kinggems Account. </p>
<p style="margin: 4px 0 10px;">* How to <a href='<?=Url::to(['site/question'], true);?>' target='_blank' style='text-decoration: none; color: #ffc107'>top up money?</a></p>
<p style="margin: 4px 0 10px;">* How to <a href='<?=Url::to(['site/question'], true);?>' target='_blank' style='text-decoration: none; color: #ffc107'>order / buy gems?</a></p>

<p style="margin: 4px 0 10px;">We hope you enjoy your new account!</p>
<p style="margin: 4px 0 10px;">The Kinggems community team,</p>
<div style="text-align: center;">
<img src="<?=Yii::$app->params['frontend_url'];?>/images/bg-popup-wallet-now.png" width="300px" height="200px" style="border: solid 3px #ffc107">
</div>
