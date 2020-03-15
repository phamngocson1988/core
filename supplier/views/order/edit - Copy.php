<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use supplier\models\Game;
use supplier\models\Order;
use supplier\models\OrderFile;
use supplier\models\OrderSupplier;
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
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-bubble font-green-sharp"></i>
          <span class="caption-subject font-green-sharp sbold">Toastr Notification Demo</span>
        </div>
        <div class="actions">
          <div class="btn-group">
            <a class="btn green-haze btn-outline btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Actions
            <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu pull-right">
              <li>
                <a href="javascript:;"> Option 1</a>
              </li>
              <li class="divider"> </li>
              <li>
                <a href="javascript:;">Option 2</a>
              </li>
              <li>
                <a href="javascript:;">Option 3</a>
              </li>
              <li>
                <a href="javascript:;">Option 4</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php echo $this->render('@supplier/views/order/_step.php', ['order' => $model]);?>
        <div class="row">
          <div class="col-md-3">
            <table class="table table-hover table-striped table-bordered">
              <tr>
                <td> Order ID </td>
                <td><?=$order->id;?></td>
              </tr>
              <?php if ($order->bulk) : ?>
              <tr>
                <td> Order detail</td>
                <td><?=nl2br($order->raw);?></td>
              </tr>
              <?php else : ?>
              <tr>
                <td> Username </td>
                <td><?=$order->username;?></td>
              </tr>
              <tr>
                <td> Password </td>
                <td><?=$order->password;?></td>
              </tr>
              <tr>
                <td> Tên nhân vật </td>
                <td><?=$order->character_name;?></td>
              </tr>
              <tr>
                <td> Platform </td>
                <td><?=$order->platform;?></td>
              </tr>
              <tr>
                <td> Login method </td>
                <td><?=$order->getLoginMethod();?></td>
              </tr>
              <tr>
                <td> Recover Code </td>
                <td><?=$order->recover_code;?></td>
              </tr>
              <tr>
                <td> Server </td>
                <td><?=$order->server;?></td>
              </tr>
              <tr>
                <td> Ghi chú </td>
                <td><?=$order->note;?></td>
              </tr>
              <?php endif;?>
            </table>
            <?php if ($model->isApprove() && !$order->hasCancelRequest()) : ?>
            <a href="<?=Url::to(['order/move-to-processing', 'id' => $model->id]);?>" class="btn green" data-toggle="modal" data-target="#go_processing"><i class="fa fa-angle-right"></i> Xác nhận login thành công</a>
            <?php endif;?>
            <?php if ($model->isProcessing()) : ?>
              <a class="btn green" id="completeBtn"><i class="fa fa-angle-right"></i> Chuyến tới trạng thái Completed</a>
            <?php endif;?>
            <?php if ($order->hasCancelRequest() && $model->isApprove()) :?>
            <button type="button" class="btn btn-outline dark" id="cleartoasts">Đồng ý hủy đơn</button>
            <?php endif;?>
          </div>
          <div class="col-md-3">
            <div class="form-group" id="toastTypeGroup">
              <label>Toast Type</label>
              <div class="mt-radio-list">
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="toasts" value="success" checked/>Success
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="toasts" value="info" />Info
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="toasts" value="warning" />Warning
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="toasts" value="error" />Error
                <span></span>
                </label>
              </div>
            </div>
            <div class="form-group" id="positionGroup">
              <label>Position</label>
              <div class="mt-radio-list">
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="positions" value="toast-top-right" checked/>Top Right
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="positions" value="toast-bottom-right" />Bottom Right
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="positions" value="toast-bottom-left" />Bottom Left
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="positions" value="toast-top-left" />Top Left
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="positions" value="toast-top-center" />Top Center
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="positions" value="toast-bottom-center" />Bottom Center
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline" l>
                <input type="radio" name="positions" value="toast-top-full-width" />Top Full Width
                <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                <input type="radio" name="positions" value="toast-bottom-full-width" />Bottom Full Width
                <span></span>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="controls">
                <label class="control-label" for="showEasing">Show Easing</label>
                <input id="showEasing" type="text" placeholder="swing, linear" class="form-control input-small" value="swing" />
                <label class="control-label" for="hideEasing">Hide Easing</label>
                <input id="hideEasing" type="text" placeholder="swing, linear" class="form-control input-small" value="linear" />
                <label class="control-label" for="showMethod">Show Method</label>
                <input id="showMethod" type="text" placeholder="show, fadeIn, slideDown" class="form-control input-small" value="fadeIn" />
                <label class="control-label" for="hideMethod">Hide Method</label>
                <input id="hideMethod" type="text" placeholder="hide, fadeOut, slideUp" class="form-control input-small" value="fadeOut" /> 
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="controls">
                <label class="control-label" for="showDuration">Show Duration</label>
                <input id="showDuration" type="text" placeholder="ms" class="form-control input-small" value="1000" />
                <label class="control-label" for="hideDuration">Hide Duration</label>
                <input id="hideDuration" type="text" placeholder="ms" class="form-control input-small" value="1000" />
                <label class="control-label" for="timeOut">Time out</label>
                <input id="timeOut" type="text" placeholder="ms" class="form-control input-small" value="5000" />
                <label class="control-label" for="timeOut">Extended time out</label>
                <input id="extendedTimeOut" type="text" placeholder="ms" class="form-control input-small" value="1000" /> 
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn green" id="showtoast">Show Toast</button>
            <button type="button" class="btn btn-outline dark" id="cleartoasts">Clear Toasts</button>
            <button type="button" class="btn btn-outline dark" id="clearlasttoast">Clear Last Toast</button>
          </div>
        </div>
        <div class="row margin-top-30">
          <div class="col-md-12">
            <pre id='toastrOptions' class="well">Settings...
            </pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
        <div class="tabbable-bordered">
          <ul class="nav nav-tabs" role="tablist">
            <li class="active">
              <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
            </li>
            <li>
              <a href="#images" data-toggle="tab"> Hình ảnh</a>
            </li>
            <li>
              <a href="#complain" data-toggle="tab"> Trợ giúp</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_general">
              <?php if ($model->isProcessing() || $model->isCompleted() ||  $model->isConfirmed()) :?>
              <div class="row">
                <div class="col-md-12 col-sm-12">
                  <div class="portlet grey-cascade box">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-cogs"></i>Game
                      </div>
                    </div>
                    <div class="portlet-body">
                      <?php echo $this->render('@supplier/views/order/_unit.php', ['order' => $model]);?>
                    </div>
                  </div>
                </div>
              </div>
              <?php endif;?>
              <div class="row">
                <div class="col-md-12 col-sm-12">
                  <div class="portlet blue-hoki box">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-cogs"></i>Order Details
                      </div>
                    </div>
                    <div class="portlet-body" id="game_account">
                      <div class="row static-info">
                        <div class="col-md-5">Order ID: </div>
                        <div class="col-md-7"><?=$order->id;?></div>
                      </div>
                      <?php if ($order->bulk) : ?>
                      <div class="row static-info">
                        <div class="col-md-5">Order detail: </div>
                        <div class="col-md-7"><?=nl2br($order->raw);?></div>
                      </div>
                      <?php else : ?>
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
                      <?php endif;?>
                      <div class="form-actions">
                        <div class="row">
                          <div class="col-md-offset-3 col-md-9">
                            <a href="<?=Url::to(['order/index']);?>" class="btn default"><i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
                          </div>
                        </div>
                      </div>
                      <?php if ($model->isApprove() && !$order->hasCancelRequest()) : ?>
                      <div class="form-actions">
                        <div class="row">
                          <div class="col-md-offset-3 col-md-9">
                            <a href="<?=Url::to(['order/move-to-processing', 'id' => $model->id]);?>" class="btn red btn-outline sbold" data-toggle="modal" data-target="#go_processing"><i class="fa fa-angle-right"></i> Xác nhận LOG IN THÀNH CÔNG</a>
                          </div>
                          
                        </div>
                      </div>
                      <?php endif;?>

                      <?php if ($model->isProcessing()) : ?>
                      <div class="form-actions">
                        <div class="row">
                          <div class="col-md-offset-3 col-md-9">
                            <a class="btn red btn-outline sbold" id="completeBtn"><i class="fa fa-angle-right"></i> Chuyến tới trạng thái Completed</a>
                          </div>
                        </div>
                      </div>
                      <?php endif;?>
                      
                      <?php if ($order->hasCancelRequest()) :?>
                      <div class="form-actions">
                        <div class="row">
                          <div class="col-md-offset-3 col-md-9">
                            <?php if ($model->isApprove()) : ?>
                            <a href="<?=Url::to(['order/reject', 'id' => $model->id]);?>" class="btn green" id="cancel_order"><i class="fa fa-check"></i> Đồng ý hủy đơn</a>
                            <?php endif;?>
                          </div>
<?php
$cancelOrderJs = <<< JS
$('#cancel_order').ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn hủy đơn hàng?',
  callback: function(element, data) {
    console.log('data', data);
    window.location.href = data.pendingUrl;
  }
});
JS;
$this->registerJs($cancelOrderJs)
?>
                        </div>
                      </div>
                      <?php endif;?>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-12">
                </div>
              </div>
            </div>
            <div class="tab-pane" id="images">
              <div class="row" style="margin-bottom: 20px">
                <div class=col-md-12>
                  <a class="btn red btn-outline sbold" id="uploadElement">Tải hình ảnh trước khi hoàn thành</a>
                  <input type="file" id="uploadEvidence" name="uploadEvidence[]" style="display: none" multiple accept="image/*"/>
                </div>
              </div>
              <div class="row" id="evidences">
                <?php echo $this->render('@supplier/views/order/_evidence.php', ['images' => $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE), 'can_edit' => true]);?>
              </div>
              <hr/>
              <div class="row" style="margin-bottom: 20px">
                <div class=col-md-12>
                  <a class="btn red btn-outline sbold" id="uploadElementAfter">Tải hình ảnh sau khi hoàn thành</a>
                  <input type="file" id="uploadEvidenceAfter" name="uploadEvidenceAfter[]" style="display: none" multiple accept="image/*"/>
                </div>
              </div>
              <div class="row" id="evidences_after">
                <?php echo $this->render('@supplier/views/order/_evidence.php', ['images' => $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_AFTER), 'can_edit' => true]);?>
              </div>
<?php
$imageJs = <<< JS
var upload = new AjaxUploadFile({
  trigger_element: '#uploadElement', 
  file_element: '#uploadEvidence',
  file_options: {resize: '500xauto'}
});
upload.callback = function(result) {
  result.forEach(function(element) {
    $.ajax({
      url: '###ADD_EVIDENCE_URL###',
      type: 'POST',
      dataType : 'json',
      data: {'OrderFile[file_id]': element.id},
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            alert('Error occur with #' + element.id);
            return false;
        } else {
          $('#evidences').html(result.data.html);
        }
      },
    });
  });
}
var uploadAfter = new AjaxUploadFile({
  trigger_element: '#uploadElementAfter', 
  file_element: '#uploadEvidenceAfter',
  file_options: {resize: '500xauto'}
});
uploadAfter.callback = function(result) {
  result.forEach(function(element) {
    $.ajax({
      url: '###ADD_EVIDENCE_AFTER_URL###',
      type: 'POST',
      dataType : 'json',
      data: {'OrderFile[file_id]': element.id},
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            alert('Error occur with #' + element.id);
            return false;
        } else {
          $('#evidences_after').html(result.data.html);
        }
      },
    });
  });
}
JS;
$addEvidenceUrl = Url::to(['order/add-evidence-image', 'id' => $order->id]);
$imageJs = str_replace('###ADD_EVIDENCE_URL###', $addEvidenceUrl, $imageJs);
$addEvidenceAfterUrl = Url::to(['order/add-evidence-image', 'id' => $order->id, 'type' => 'after']);
$imageJs = str_replace('###ADD_EVIDENCE_AFTER_URL###', $addEvidenceAfterUrl, $imageJs);
$this->registerJs($imageJs);
?>
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
                        <a href="<?=Url::to(['order/template', 'id' => $order->id]);?>"  data-target="#complain_template" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Gửi phản hồi theo mẫu</a>
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
                                <a href="javascript:;" class="timeline-body-title font-blue-madison"><?=$complain->isCustomer() ? 'Khách hàng' : $complain->sender->name;?></a>
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

<!-- Complain template -->
<div class="modal fade modal-scroll" id="complain_template" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<?php
$complainJs = <<< JS
$(document).on('submit', 'body .complain-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      if (!result.status)
       alert(result.errors);
      else 
        location.reload();
    },
  });
  return false;
});
JS;
$this->registerJs($complainJs);
?>

<!-- End complain template -->

<!-- Go processing -->
<div class="modal fade" id="go_processing" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<!-- End Go processing -->
<?php
$moveProcessingJs = <<< JS
// supplier
$(document).on('submit', 'body #move-processing-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      console.log(result);
      if (!result.status)
       alert(result.errors);
      else 
        window.location.href = result.editUrl;
    }
  });
  return false;
});
JS;
$this->registerJs($moveProcessingJs)
?>

<!-- End Go completed -->
<div class="modal fade" id="go_completed" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Chuyển tới trạng thái Completed</h4>
      </div>
      <?= Html::beginForm(['order/move-to-completed', 'id' => $model->id], 'post', ['class' => 'form-horizontal form-row-seperated', 'id' => 'move-completed-form']) ?>
      <div class="modal-body"> 
        <p>Bạn có chắc chắn muốn chuyển đơn hàng này sang trạng thái "Completed"</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Hủy</button>
        <button type="submit" class="btn green">Xác nhận</button>
      </div>
      <?= Html::endForm();?>
    </div>
  </div>
</div>
<?php
$moveCompletedJs = <<< JS
var moveCompletedForm = new AjaxFormSubmit({element: '#move-completed-form'});
moveCompletedForm.success = function (data, form) {
  location.reload();
};
moveCompletedForm.error = function (errors) {
  alert(errors);
  return false;
};
JS;
$this->registerJs($moveCompletedJs)
?>
<!-- End Go completed -->

<!-- End Go partial -->
<div class="modal fade" id="go_partial" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Xác nhận hoàn thành một phần đơn hàng</h4>
      </div>
      <?= Html::beginForm(['order/move-to-partial', 'id' => $model->id], 'post', ['class' => 'form-horizontal form-row-seperated', 'id' => 'move-partial-form']) ?>
      <div class="modal-body"> 
        <p>Bạn chưa nhập đủ số lượng game cho đơn hàng. Nếu hoàn tất lúc này, hệ thống sẽ ghi nhận số lượng hoàn thành của bạn là <strong class="doing"></strong> / <strong class="quantity"></strong>. Bạn có chắc chắn muốn thực hiện điều này?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Hủy</button>
        <button type="submit" class="btn green">Xác nhận</button>
      </div>
      <?= Html::endForm();?>
    </div>
  </div>
</div>
<?php
$moveCompletedJs = <<< JS
var moveCompletedForm = new AjaxFormSubmit({element: '#move-partial-form'});
moveCompletedForm.success = function (data, form) {
  location.reload();
};
moveCompletedForm.error = function (errors) {
  alert(errors);
  return false;
};
JS;
$this->registerJs($moveCompletedJs)
?>
<!-- End Go partial -->

<?php
$completeModalJs = <<< JS
$('body').on('click', '#completeBtn', function() {
  var quantity = $('#doing_unit_progress').attr('aria-valuemax');
  var doing = $('#current_doing_unit').text();
  quantity = parseFloat(quantity);
  doing = parseFloat(doing);
  if (doing == 0) {
    alert('Vui lòng nhập số lượng game mà bạn đã nạp.');
    return false;
  }
  if (doing == quantity) {
    $('#go_completed').modal('show');
  } else {
    $('#go_partial').find('.doing').text(doing);
    $('#go_partial').find('.quantity').text(quantity);
    $('#go_partial').modal('show');
  }
});
JS;
$this->registerJs($completeModalJs)
?>

<!-- Cancel order -->
<?php
$cancelOrderJs = <<< JS
$('#cancel_order').ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn hủy đơn hàng?',
  callback: function(element, data) {
    console.log('data', data);
    window.location.href = data.pendingUrl;
  }
});
JS;
$this->registerJs($cancelOrderJs)
?>
<!-- End cancel order -->