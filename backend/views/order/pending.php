<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Game;
use common\models\Product;
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
      <span>Chỉnh sửa đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chỉnh sửa đơn hàng</h1>
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
              <!-- <li>
                <a href="#images" data-toggle="tab"> Hình ảnh</a>
              </li> -->
              <li>
                <a href="#complain" data-toggle="tab"> Phản hồi</a>
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
                          <div class="col-md-6">
                              <div class="input-group">
                                  <input type="number" id="doing_unit" class="form-control">
                                  <span class="input-group-btn">
                                      <button class="btn btn-default" id="update_unit" type="button">Nạp game</button>
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
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated form']]);?>
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Thông tin nạp game
                        </div>
                      </div>
                      <div class="portlet-body" id="game_account">
                        <div class="row static-info">
                          <div class="col-md-5"> Username: </div>
                          <div class="col-md-7"> 
                            <?=$form->field($order, 'username', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Password: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'password', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Tên nhân vật: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'character_name', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Platform: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'platform', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->dropDownList(['ios' => 'Ios', 'android' => 'Android'])->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Login method: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'login_method', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->dropDownList(['google' => 'Google', 'facebook' => 'Facebook'])->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Recover Code: </div>
                          <div class="col-md-7"> 
                            <?=$form->field($order, 'recover_code', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Server: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'server', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Ghi chú: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'note', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <a href="<?=Url::to(['order/index']);?>" class="btn default"><i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
                                    <button type="submit" class="btn green">Cập nhật</button>
                                    <a class="btn red btn-outline sbold" data-toggle="modal" href="#next"><i class="fa fa-angle-right"></i> Chuyến tới trạng thái Processing</a>
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
                    <?php ActiveForm::end()?>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <?php echo $this->render('@backend/views/order/_detail.php', ['order' => $order]);?>
                    <?php echo $this->render('@backend/views/order/_customer.php', ['order' => $order]);?>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="images">
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <a class="btn red btn-outline sbold" id="before_image">Hình trước</a>
                    <input type="file" id="file_before_image" name="before_image" style="display: none" />
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <a class="btn red btn-outline sbold" id="after_image">Hình sau</a>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="complain">
                <!-- Start -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="portlet light portlet-fit bordered">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="icon-microphone font-green"></i>
                          <span class="caption-subject bold font-green uppercase"> Phản hồi từ khách hàng </span>
                        </div>
                        <div class="actions">
                          <a href="#complain_template" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Gửi phản hồi</a>
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="timeline">
                          <?php foreach ($order->complains as $complain):?>
                          <div class="timeline-item">
                            <div class="timeline-badge">
                              <?php if ($complain->sender->avatarImage) :?>
                              <img class="timeline-badge-userpic" src="<?=$complain->sender->getAvatarUrl();?>"> 
                              <?php else : ?>
                                <div class="timeline-icon">
                                  <i class="icon-user-following font-green-haze"></i>
                                </div>
                              <?php endif; ?>
                            </div>
                            <div class="timeline-body">
                              <div class="timeline-body-arrow"> </div>
                              <div class="timeline-body-head">
                                <div class="timeline-body-head-caption">
                                  <a href="javascript:;" class="timeline-body-title font-blue-madison"><?=$complain->sender->name;?></a>
                                  <span class="timeline-body-time font-grey-cascade">Phản hồi vào lúc <?=$complain->created_at;?></span>
                                </div>
                              </div>
                              <div class="timeline-body-content">
                                <span class="font-grey-cascade"><?=$complain->content;?></span>
                              </div>
                            </div>
                          </div>
                          <?php endforeach;?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End -->
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
        <h4 class="modal-title">Chuyển tới trạng thái Processing</h4>
      </div>
      <?php $nextForm = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated', 'id' => 'next-form'], 'action' => Url::to(['order/move-to-processing'])]);?>
      <?=$nextForm->field($updateStatusForm, 'id', [
        'template' => '{input}', 
        'options' => ['container' => false]
      ])->hiddenInput(['value' => $order->id])->label(false);?>
      <div class="modal-body"> 
          <p>Bạn có chắc chắn muốn chuyển đơn hàng này sang trạng thái "Processing"</p>
          <p id="doing_unit_notice" style="display: none">Số đơn vị game của bạn vẫn chưa được cập nhật đủ, nếu chuyển qua trạng thái "Processing", toàn bộ số đơn vị game đang thực hiện sẽ được cập nhật đúng bằng số đơn vị game cần nhập của đơn hàng.</p>
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

<div class="modal fade" id="complain_template" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Chọn một câu trả lời để phản hồi đến khách hàng</h4>
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
                <?= Html::beginForm(['order/send-complain'], 'POST', ['class' => 'send-form']); ?>
                  <?= Html::hiddenInput('order_id', $order->id); ?>
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
var nextForm = new AjaxFormSubmit({element: '#next-form'});
nextForm.success = function (data, form) {
  window.location.href = data.next;
}

var sendForm = new AjaxFormSubmit({element: '.send-form'});
sendForm.success = function (data, form) {
  location.reload();
}

// Update doing unit
$('#update_unit').on('click', function(e) {
  console.log($('#doing_unit').val());
  e.preventDefault();
  e.stopImmediatePropagation();
  $.ajax({
    url: '###UPDATE_UNIT###',
    type: 'POST',
    dataType : 'json',
    data: {doing_unit: $('#doing_unit').val()},
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
          alert(result.errors);
          return false;
      } else {
        var cur = $('#doing_unit_progress').attr('aria-valuemax');
        var newpc = (result.data.total / cur) * 100;
        $('#doing_unit_progress').css('width', newpc + '%');
        $('#doing_unit_progress span').html(result.data.total + '(Complete)');
        $('#current_doing_unit').html(result.data.total);
        $('#doing_unit').val('');
      }
    },
  });
  return false;
});

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
        window.location.href = result.data.view_url;
      }
    },
  });
  return false;
});



JS;
$redirect = Url::to(['order/add-unit', 'id' => $order->id]);
$script = str_replace('###UPDATE_UNIT###', $redirect, $script);
$this->registerJs($script);
?>