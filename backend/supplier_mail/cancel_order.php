<?php
use yii\helpers\Url;
?>
<p>Kính gửi quý khách,</p>
<p>Chúng tôi rất tiếc phải thông báo rằng đơn hàng (Mã đơn hàng #<?=$order->id;?>)  đã bị hủy vì yêu cầu của người mua hoặc lý do bất khả kháng. Quý khách vui lòng ngừng thực hiện bất kỳ thao tác này trên tài khoản nói trên, và thực hiện việc đăng xuất hoàn toàn trên các thiết bị của quý khách. Xin chân thành cảm ơn sự hợp tác của quý khách.</p>
<p>Thông tin chi tiết đơn hàng: </p>
<p>
  <strong>Mã đơn hàng:</strong> <span><?=$order->id;?></span><br>
  <strong>Số lượng nạp:</strong> <span><?=number_format($orderSupplier->quantity);?></span><br>
  <strong>Tên game:</strong> <span><?=$order->game_title;?></span><br>
  <strong>Phí thực hiện đơn hàng:</strong> <span><?=number_format($orderSupplier->total_price);?></span><br>
</p>
<p>Quý khách có thể kiểm tra thêm thông tin chi tiết đơn hàng theo đường link sau: [......]</p>
<p>Phí thực hiện đơn hàng này sẽ KHÔNG được tính vào doanh thu của quý khách.</p>