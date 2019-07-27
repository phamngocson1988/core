<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Xin chào <?= $mail->name;?>, bạn được mời làm thành viên quản trị của website kinggems.us. Vui lòng nhấn vào link bên dưới để hoàn tất quá trình đăng nhập hệ thống</h2>
<strong>Link kích hoạt:</strong> <?=Html::a('Nhấn vào đây', $activate_link, ['target' => '_blank']);?><br>
<strong>Vai trò:</strong> <?= $mail->role ?><br>