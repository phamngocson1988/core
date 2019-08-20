<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="portlet blue-hoki box">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-cogs"></i>Thông tin nạp game
    </div>
  </div>
  <div class="portlet-body" id="game_account">
    <div class="row static-info">
      <div class="col-md-5">Username: </div>
      <div class="col-md-7"><?=$order->username;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5">Password: </div>
      <div class="col-md-7"><?=$order->password;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5">Tên nhân vật: </div>
      <div class="col-md-7"><?=$order->character_name;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5">Platform: </div>
      <div class="col-md-7"><?=$order->platform;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5">Login method: </div>
      <div class="col-md-7"><?=$order->login_method;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5">Recover Code: </div>
      <div class="col-md-7"><?=$order->recover_code;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5">Server: </div>
      <div class="col-md-7"><?=$order->server;?></div>
    </div>
    <div class="row static-info">
      <div class="col-md-5">Ghi chú: </div>
      <div class="col-md-7"><?=$order->note;?></div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <a href="<?=Url::to(['order/index']);?>" class="btn default"><i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
                <a class="btn red btn-outline sbold" data-toggle="modal" href="#next"><i class="fa fa-angle-right"></i> Chuyến tới trạng thái Pending</a>
            </div>
        </div>
    </div>
    <?php if ($order->hasCancelRequest()) :?>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <a href="<?=Url::to(['order/approve', 'id' => $order->id]);?>" class="btn green" id="cancel_order"><i class="fa fa-check"></i> Đồng ý hủy đơn</a>
                <a class="btn red btn-outline sbold" data-toggle="modal" href="#disapprove"><i class="fa fa-ban"></i> Không chấp nhận</a>
            </div>
        </div>
    </div>
    <?php endif;?>
  </div>
</div>

<div class="modal fade" id="disapprove" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Lý do không chấp nhận hủy đơn hàng</h4>
      </div>
      <div class="modal-body" style="height: 200px; position: relative; overflow: auto; display: block;"> 
        <table class="table">
          <thead>
            <tr>
              <th scope="col" width="5%">#</th>
              <th scope="col" width="90%">Nội dung</th>
              <th scope="col" width="5%">Chọn</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($template_list as $template_item) :;?>
            <tr>
              <td><?=$template_item->id;?></td>
              <td><?=$template_item->content;?></td>
              <td>
                <?= Html::beginForm(['order/disapprove', 'id' => $order->id], 'POST', ['class' => 'send-form']); ?>
                  <?= Html::hiddenInput('template_id', $template_item->id); ?>
                  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Gửi</button>
                <?= Html::endForm(); ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php
$script = <<< JS
$('#cancel_order').on('click', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  $.ajax({
    url: $(this).prop('href'),
    type: 'POST',
    dataType : 'json',
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
          alert(result.errors);
          return false;
      } else {
        window.location.href = result.data.url;
      }
    },
  });
  return false;
});

var sendForm = new AjaxFormSubmit({element: '.send-form'});
sendForm.success = function (data, form) {
  location.reload();
}

JS;
$this->registerJs($script);
?>