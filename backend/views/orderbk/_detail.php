<?php
use common\components\helpers\FormatConverter;
?>
<div class="portlet blue-hoki box">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-cogs"></i>Order Details 
    </div>
  </div>
  <div class="portlet-body">
    <div class="row static-info">
      <div class="col-md-5"> Mã đơn hàng: </div>
      <div class="col-md-7"> <?=$order->id;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Thời gian tạo: </div>
      <div class="col-md-7"> <?=$order->created_at;?> </div>
    </div>
    <?php if ($order->isProcessingOrder() || $order->isCompletedOrder()) :?>
    <div class="row static-info">
      <div class="col-md-5"> Thời gian nhận xử lý: </div>
      <div class="col-md-7"> <?=$order->process_start_time;?> </div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Thời gian trả đơn: </div>
      <div class="col-md-7"> <?=$order->process_end_time;?> </div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Tốc độ xử lý: </div>
      <div class="col-md-7"> <?=round($order->getProcessDurationTime() / 60, 1);?> (minutes)</div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Trung bình xử lý: </div>
      <div class="col-md-7"> <?=round(($order->getProcessDurationTime() / 60) / $order->getGamePack(), 1);?> minutes / pack</div>
    </div>
    <?php endif;?>
    <div class="row static-info">
      <div class="col-md-5"> Order Status: </div>
      <div class="col-md-7">
        <?=$order->getStatusLabel();?>
      </div>
    </div>
    <?php if (Yii::$app->user->can('admin')) :?>
    <?php if ($order->total_discount) :?>
    <div class="row static-info">
      <div class="col-md-5"> Sub total: </div>
      <div class="col-md-7"> <?=number_format($order->sub_total_price);?> </div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Discount: </div>
      <div class="col-md-7"> <?=number_format($order->total_discount);?> </div>
    </div>
    <?php endif;?>
    <div class="row static-info">
      <div class="col-md-5"> Total price: </div>
      <div class="col-md-7"> <?=number_format($order->total_price);?> </div>
    </div>
    <?php endif;?>
    <div class="row static-info">
      <div class="col-md-5"> Cổng thanh toán: </div>
      <div class="col-md-7"> <?=$order->payment_method;?> </div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Phương thức thanh toán: </div>
      <div class="col-md-7"> <?=$order->payment_type;?> </div>
    </div>
  </div>
</div>