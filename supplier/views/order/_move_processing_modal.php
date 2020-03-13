<?php
use yii\helpers\Html;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Chuyển tới trạng thái Processing</h4>
</div>
<?= Html::beginForm(['order/move-to-processing', 'id' => $id], 'post', ['class' => 'form-horizontal form-row-seperated', 'id' => 'move-processing-form']) ?>
<div class="modal-body"> 
  <p>Xác nhận bạn đã login vào đơn hàng thành công và bắt đầu tiến hành xử lý đơn hàng này</p>
</div>
<div class="modal-footer">
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Hủy</button>
  <button type="submit" class="btn green">Xác nhận</button>
</div>
<?= Html::endForm();?>
