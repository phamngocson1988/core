<?php
use yii\helpers\Url;
?>
<p>Kính gửi quý khách,</p>
<p>Hoanggianapgame xin xác nhận lệnh rút tiền của khách đã được tiếp nhận theo thông tin chi tiết nhu sau:</p>
<p>
  <strong>Số tiền:</strong> <span><?=$order->id;?></span><br>
  <strong>Thông tin tài khoản thụ hưởng:</strong> <span><?=number_format($orderSupplier->quantity);?></span><br>
  <strong>Phương thức rút tiền:</strong> <span><?=$order->game_title;?></span><br>
  <strong>Thời gian dự kiến hoàn thành:</strong> <span><?=number_format($orderSupplier->total_price);?></span><br>
</p>
<p>Thời gian tiến hành (ước tính): ......</p>
<p>Xem quy định rút tiền và thời gian tiến hành giao dịch. (Click here- hyperlink quy định rút tiền)</p>
<p>Xin lưu ý đây là email mang tính chất thông báo, xin vui lòng không phản hồi lại email này.</p>