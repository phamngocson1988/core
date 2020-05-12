<?php
use yii\helpers\Url;
$link = Url::to(['site/activate', 'id' => $user->id, 'key' => $user->auth_key], true);
?>
<p style="margin: 4px 0 10px;">Dear <?=$user->name;?></p>
<p style="margin: 4px 0 10px;">To activate your Kinggems Account, please verify your email address.</p>
<p style="margin: 4px 0 10px;"><a href='<?=$link;?>' target='_blank' style='text-decoration: none; color: #ffc107'>Confirm your email.</a></p>

<p style="margin: 4px 0 10px;">Or, copy and paste the following URL into your browser:</p>
<p style="margin: 4px 0 10px;"><a href='<?=$link;?>' target='_blank'><?=$link;?></a></p>