<?php
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Your order #<?=$mail->id;?> has been completed now.</h2><br/>
<strong>Review the order and comfirm it:</strong> <a href="<?=$order_link;?>" target="_blank">View detail</a><br>