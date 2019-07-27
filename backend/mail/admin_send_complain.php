<?php
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>You have received a notification from admin for order #<?=$mail->order_id;?></h2>
<strong>Content:</strong> <?=$template->content;?><br/>
<a href="<?=$order_link;?>" target="_blank">View detail</a><br>