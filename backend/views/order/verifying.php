<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use backend\models\Game;
use backend\models\Order;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['order/index'])?>">Quản lý đơn hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Xem đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Xem đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase"> Order #<?=$order->id;?>
              <span class="hidden-xs">| <?=$order->created_at;?> </span>
            </span>
          </div>
        </div>
        <div class="portlet-body">
          <?php echo $this->render('@backend/views/order/_step.php', ['order' => $order]);?>
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs" role="tablist">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="portlet grey-cascade box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Game
                        </div>
                      </div>
                      <div class="portlet-body">
                        <?php echo $this->render('@backend/views/order/_unit.php', ['order' => $order]);?>
                        <div class="row static-info">
                          <div class="col-md-12">
                            <strong>Số game đã nạp: <?=$order->doing_unit;?></strong>
                          </div>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Order Details
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
                          <div class="col-md-7"><?=$order->getLoginMethod();?></div>
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
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <?php echo $this->render('@backend/views/order/_detail.php', ['order' => $order]);?>
                    <?php echo $this->render('@backend/views/order/_customer.php', ['order' => $order]);?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
<div class="modal fade" id="next" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Chuyển tới trạng thái Pending</h4>
      </div>
      <?php $nextForm = ActiveForm::begin(['options' => ['class' => 'form-row-seperated', 'id' => 'next-form'], 'action' => Url::to(['order/move-to-pending', 'id' => $order->id])]);?>
      <div class="modal-body"> 
          <p>Bạn có chắc chắn muốn chuyển đơn hàng này sang trạng thái "Pending". Hãy chắc chắn rằng đơn hàng này đã được thanh toán</p>
          <?=$nextForm->field($order, 'payment_method')->textInput();?>
          <?=$nextForm->field($order, 'payment_id')->textInput();?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
        <button type="submit" class="btn green">Xác nhận</button>
      </div>
      <?php ActiveForm::end();?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php
$script = <<< JS
var nextForm = new AjaxFormSubmit({element: '#next-form'});
nextForm.success = function (data, form) {
  window.location.href = data.next;
};

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

JS;
$this->registerJs($script);
?>