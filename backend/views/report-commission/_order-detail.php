<?php
use common\components\helpers\StringHelper;
$isStaff = Yii::$app->user->isRole('saler') || Yii::$app->user->isRole('orderteam') || Yii::$app->user->isRole('saler_manager') || Yii::$app->user->isRole('orderteam_manager');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Chi tiết đơn hàng #<?=$id;?></h4>
</div>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
      <table class="table table-bordered">
        <tbody>
          <tr>
            <td class="left"><strong>ID</strong></td>
            <td class="center">#<?=$order->id;?></td>
          </tr>
          <tr>
            <td class="left"><strong>Tên game</strong></td>
            <td class="center"><?=$order->game_title;?></td>
          </tr>
          <tr>
            <td class="left"><strong>Tên khách hàng</strong></td>
            <td class="center"><?=$order->customer_name;?></td>
          </tr>
          <?php if (!$isStaff) : ?>
          <tr>
            <td class="left"><strong>Đơn giá</strong></td>
            <td class="center"><?=StringHelper::numberFormat($order->price, 1);?> USD</td>
          </tr>
          <tr>
            <td class="left"><strong>Số lượng</strong></td>
            <td class="center"><?=StringHelper::numberFormat($order->quantity);?></td>
          </tr>
          <tr>
            <td class="left"><strong>Tỷ giá</strong></td>
            <td class="center"><?=StringHelper::numberFormat($order->rate_usd);?> ₫</td>
          </tr>
          <?php endif;?>
          
          <tr>
            <td class="left"><strong>Lợi nhuận mong đợi</strong></td>
            <td class="center"><?=StringHelper::numberFormat($order->expected_profit);?> ₫</td>
          </tr>
          <tr>
            <td class="left"><strong>Lợi nhuận thực tế</strong></td>
            <td class="center"><?=StringHelper::numberFormat($order->real_profit);?> ₫</td>
          </tr>
          <?php if ($type === 'order') :?>
          <tr>
            <td class="left"><strong>Hoa hồng đơn hàng</strong></td>
            <td class="center">
              <?php $key = sprintf("%s_order_commission", $role);?>
              <?=StringHelper::numberFormat($order->$key);?> ₫
            </td>
          </tr>
          <?php elseif ($type === 'sellout') :?>
          <tr>
            <td class="left"><strong>Hoa hồng sellout</strong></td>
            <td class="center">
              <?php $key = sprintf("%s_sellout_commission", $role);?>
              <?=StringHelper::numberFormat($order->$key);?> ₫
            </td>
          </tr>
          <?php endif;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
</div>