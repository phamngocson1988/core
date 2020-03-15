<?php 
use yii\helpers\Html;
?>
<!-- <div class="table-responsive">
  <table class="table table-hover table-bordered table-striped">
    <thead>
      <tr>
        <th> Tên game </th>
        <th> Số lượng cần nạp </th>
        <th> Số lượng đã nạp </th>
        <th> Số tiền cho game </th>
        <th> Số tiền nhận được </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?=$order->getGameTitle();?></td>
        <td><?=$order->quantity;?></td>
        <td><?=$order->doing;?></td>
        <td><?=number_format($order->price);?></td>
        <td><?=number_format($order->total_price);?></td>
      </tr>
    </tbody>
  </table>
</div> -->
<?php if ($order->isProcessing()) :?>
<div class="row static-info">
  <?= Html::beginForm(['order/add-quantity', 'id' => $order->id], 'post', ['id' => 'update-unit-form']) ?>
  <div class="col-md-6">
      <div class="input-group">
          <input type="text" id="doing_unit" name="doing" class="form-control">
          <span class="input-group-btn">
            <button class="btn btn-default" id="update_unit" type="submit">Xác nhận</button>
          </span>
      </div><!-- /input-group -->
  </div>
  <div class="col-md-6">
      <div class="progress progress-striped active">
          <div id="doing_unit_progress" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=$order->quantity;?>" aria-valuemin="0" aria-valuemax="<?=$order->quantity;?>" style="width: <?=$order->getPercent();?>%">
              <span id='current_doing_unit'><?=$order->doing;?></span> / <?=$order->quantity;?>
          </div>
      </div>
  </div>
  <?= Html::endForm();?>
</div>
<?php
$progress = <<< JS
var updateUnitForm = new AjaxFormSubmit({element: '#update-unit-form'});
updateUnitForm.success = function (data, form) {
  console.log(data);
var cur = $('#doing_unit_progress').attr('aria-valuemax');
var newpc = (data.total / cur) * 100;
  $('#doing_unit_progress').css('width', newpc + '%');
  $('#doing_unit_progress span').html(data.total + '(Complete)');
  $('#current_doing_unit').html(data.total);
  $('#doing_unit').val('');
};
updateUnitForm.error = function (errors) {
  alert(errors);
  return false;
}
JS;
$this->registerJs($progress);
?>                        
<?php endif;?>