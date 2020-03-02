<?php
use yii\helpers\Url;
?>
<p>Kính gửi quý khách,</p>
<p>Hoanggianapgame được nhận thông báo khiếu nại từ người mua đơn hàng (Mã đơn hàng #<?=$order->id;?>)</p>
<p>Thông tin chi tiết đơn hàng: </p>
<p>
  <strong>Mã đơn hàng:</strong> <span><?=$order->id;?></span><br>
  <strong>Số lượng nạp:</strong> <span><?=number_format($orderSupplier->quantity);?></span><br>
  <strong>Tên game:</strong> <span><?=$order->game_title;?></span><br>
  <strong>Phí thực hiện đơn hàng:</strong> <span><?=number_format($orderSupplier->total_price);?></span><br>
</p>
<p>Quý khách có thể kiểm tra thêm thông tin chi tiết đơn hàng theo đường link sau: [......]</p>
<p>Nội dung khiếu nại: ...</p>
<p>Quý khách vui lòng kiểm tra thông tin khiếu nại trên và gửi lại phản hồi qua email này trong vòng 24 tiếng, kể từ thời điểm nhận thông báo này. Nếu không nhận được bất kỳ phản hồi nào, chúng tôi buộc phải tiến hành việc hoàn tiền đơn hàng cho người mua, theo quy định của Hoanggianapgame.vn.</p>
<p>Xin chân thành cảm ơn sự hợp tác của quý khách</p>