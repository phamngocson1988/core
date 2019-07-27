<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Xin chào <?= $mail->name;?>, tài khoản khách hàng của bạn trên hệ thống kinggems.us đã được tạo bảo admin</h2>
<strong>Website:</strong> <a href="https://kinggems.us/dang-nhap.html" target="_blank">Link đăng nhập</a><br>
<strong>Tài khoản:</strong> <?= $mail->username ?><br>
<strong>Mật khẩu:</strong> <?= $mail->password ?><br>
