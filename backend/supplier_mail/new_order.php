<?php
use yii\helpers\Url;
?>
<p>Kính gửi quý khách,</p>
<p>Hoanggianapgame xin chúc mừng quý khách vừa nhận được một (01) đơn hàng mới. Vui lòng kiểm tra và thực hiện đơn hàng đúng như yêu cầu, trong thời gian sớm nhất.</p>
<p>
  <strong>Mã đơn hàng:</strong> <span><?=$order->id;?></span><br>
  <strong>Tên game:</strong> <span><?=$order->game_title;?></span><br>
  <strong>Số lượng gói:</strong> <span><?=$order->quantity;?></span><br>
  <strong>Cách thức đăng nhập:</strong> <span><?=$order->login_method;?></span><br>
  <strong>Tên đăng nhập/ ID:</strong> <span><?=$order->username;?></span><br>
  <strong>Mật khẩu:</strong> <span><?=$order->password;?></span><br>
  <strong>Server (nếu có):</strong> <span><?=$order->server;?></span><br>
  <strong>Recovery Code (nếu có):</strong> <span><?=$order->recover_code;?></span><br>
</p>
<p>Vui lòng xác nhận đơn hàng đã hoàn thành sau khi hoàn tất theo các bước hướng dẫn.</p>

