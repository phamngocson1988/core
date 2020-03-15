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
          <span class="caption-subject font-green-sharp sbold">Order #<?=$order->id;?> <span class="hidden-xs">| <?=$order->created_at;?> </span> </span>
        </div>
      </div>
      <div class="portlet-body">
        <?php echo $this->render('@supplier/views/order/_step.php', ['order' => $model]);?>
        <?php if ($model->isProcessing() || $model->isCompleted() ||  $model->isConfirmed()) :?>
        <div class="row" style="display: flex;">
            <div class="btn-group">
              <button type="button" class="btn btn-default"> 0% </button>
              <button type="button" class="btn btn-default"> 30% </button>
              <button type="button" class="btn btn-default"> 50% </button>
              <button type="button" class="btn btn-default"> 80% </button>
              <button type="button" class="btn btn-default"> 100% </button>
            </div>
            <div class="progress progress-striped active" style="width: 300px">
              <div id="doing_unit_progress" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=$model->quantity;?>" aria-valuemin="0" aria-valuemax="<?=$model->quantity;?>" style="width: <?=$model->getPercent();?>%">
                  <span id='current_doing_unit'><?=$model->doing;?></span> / <?=$model->quantity;?>
              </div>
            </div>
          </div>
        </div>
        <?php endif;?>
        <div class="row">
          <div class="col-md-3 col-sm-6">
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
          <div class="col-md-3 col-sm-6">
            <div class="dropzone dropzone-file-area" style="margin-bottom: 20px">
              <a class="sbold" id="uploadElement">Tải hình ảnh cho đơn hàng</a>
              <input type="file" id="uploadEvidence" name="uploadEvidence[]" style="display: none" multiple accept="image/*"/>
            </div>
            <div id="evidences">
                <?php echo $this->render('@supplier/views/order/_evidence.php', ['images' => $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE), 'can_edit' => true]);?>
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="portlet light portlet-fit bordered">
              <div class="portlet-title">
                <div class="caption">
                  <i class="icon-microphone font-green"></i>
                  <span class="caption-subject bold font-green uppercase"> Phản hồi từ khách hàng </span>
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
                <a href="<?=Url::to(['order/template', 'id' => $order->id]);?>"  data-target="#complain_template" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Gửi phản hồi theo mẫu</a>
              </div>
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

<!-- Image upload --> 
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
<!-- end image upload -->

<!-- update percent -->
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
<!-- end update percent -->