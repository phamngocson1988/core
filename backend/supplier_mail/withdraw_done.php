<?php
use yii\helpers\Url;
?>
<p>Kính gửi quý khách,</p>
<p>Chúng tôi xin thông báo lệnh rút tiền của quý khách (Mã giao dịch #12345678) đã được tiến hành thành công. Quý khách vui lòng kiểm tra thông tin tài khoản nhận tiền.</p>
<p>Thông tin chi tiết giao dịch: </p>
<p>
  <strong>Phương thức rút tiền:</strong> <span><?=$order->id;?></span><br>
  <strong>Số tiền:</strong> <span><?=number_format($orderSupplier->quantity);?></span><br>
  <strong>Số tài khoản:</strong> <span><?=$order->game_title;?></span><br>
  <strong>Tên ngân hàng:</strong> <span><?=number_format($orderSupplier->total_price);?></span><br>
  <strong>Tên chủ tài khoản:</strong> <span><?=number_format($orderSupplier->total_price);?></span><br>
</p>
<p>Thời gian dự kiến hoàn thành: ......</p>
<p>Xin lưu ý đây là email mang tính chất thông báo, xin vui lòng không phản hồi lại email này.</p>