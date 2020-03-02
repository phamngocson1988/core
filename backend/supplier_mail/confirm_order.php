<?php
use yii\helpers\Url;
?>
<p>Kính gửi quý khách,</p>
<p>Xin thông báo, đơn hàng (Mã đơn hàng #<?=$order->id;?>) đã được người mua xác nhận: ĐÃ NHẬN HÀNG thành công. </p>
<p>Thông tin chi tiết đơn hàng: </p>
<p>
  <strong>Mã đơn hàng:</strong> <span><?=$order->id;?></span><br>
  <strong>Số lượng nạp:</strong> <span><?=number_format($orderSupplier->quantity);?></span><br>
  <strong>Tên game:</strong> <span><?=$order->game_title;?></span><br>
  <strong>Phí thực hiện đơn hàng:</strong> <span><?=number_format($orderSupplier->total_price);?></span><br>
</p>
<p>Quý khách có thể kiểm tra thêm thông tin chi tiết đơn hàng theo đường link sau: [......]</p>
<p>Phí thực hiện đơn hàng đã được cập nhật vào số dư trên tài khoản quý khách tại hoanggianapgame.vn. Xin vui lòng kiểm tra!</p>
<p>Xin cảm ơn sự nỗ lực của quý khách trong việc hoàn thành đơn hàng! </p>
<p>Xin lưu ý đây là email mang tính chất thông báo, xin vui lòng không phản hồi lại email này.</p>