<?php 
use yii\helpers\Html;
?>
<div class="table-responsive">
  <table class="table table-hover table-bordered table-striped">
    <thead>
      <tr>
        <th> Tên game </th>
        <th> Số lượng nạp </th>
        <th> Số gói </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?=$order->game_title;?></td>
        <td><?=$order->total_unit;?></td>
        <td><?=$order->quantity;?></td>
      </tr>
    </tbody>
  </table>
</div>
<?php if (Yii::$app->user->can('orderteam') && $order->isPendingOrder()) :?>
<div class="row static-info">
  <?= Html::beginForm(['order/add-unit', 'id' => $order->id], 'post', ['id' => 'update-unit-form']) ?>
  <div class="col-md-6">
      <div class="input-group">
          <input type="number" id="doing_unit" name="doing_unit" class="form-control">
          <span class="input-group-btn">
            <button class="btn btn-default" id="update_unit" type="submit">Xác nhận</button>
          </span>
      </div><!-- /input-group -->
  </div>
  <div class="col-md-6">
      <div class="progress progress-striped active">
          <div id="doing_unit_progress" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=$order->doing_unit;?>" aria-valuemin="0" aria-valuemax="<?=$order->total_unit;?>" style="width: <?=$order->getPercent();?>%">
              <span id='current_doing_unit'><?=$order->doing_unit;?></span> / <?=$order->total_unit;?>
          </div>
      </div>
  </div>
  <?= Html::endForm();?>
<?php
$progress = <<< JS
var updateUnitForm = new AjaxFormSubmit({element: '#update-unit-form'});
updateUnitForm.success = function (data, form) {
var cur = $('#doing_unit_progress').attr('aria-valuemax');
var newpc = (data.total / cur) * 100;
$('#doing_unit_progress').css('width', newpc + '%');
$('#doing_unit_progress span').html(data.total + '(Complete)');
$('#current_doing_unit').html(data.total);
$('#doing_unit').val('');
};
JS;
$this->registerJs($progress);
?>                        
</div>
<?php endif;?>