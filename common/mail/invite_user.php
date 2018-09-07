<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Xin chào <?= $mail->name;?>, bạn được mời làm thành viên quản trị của website kinggems.us. Vui lòng sử dụng thông tin bên dưới để đăng nhập vào hệ thống</h2>
<strong>Website:</strong> <a href="https://admin.kinggems.us" target="_blank">Link đăng nhập trang quản lý admin</a><br>
<strong>Tài khoản:</strong> <?= $mail->username ?><br>
<strong>Mật khẩu:</strong> <?= $mail->password ?><br>
<strong>Vai trò:</strong> <?= $mail->role ?><br>