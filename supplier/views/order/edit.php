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
$this->registerJsFile('@web/js/complains.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<style type="text/css">
  .button-percent.active {
    background-color: #32c5d2;
  }
  .button-percent {
    border-color: #ccc;
    background-color: white;
    color: black;
    border: solid 2px #CCC;
  }
  .flex-container {
    padding: 10px;
    margin-bottom: 15px;
    height: 55px;
  }
</style>
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
      <span>Xử lý đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-settings"></i>
          <span class="caption-subject sbold">Order #<?=$order->id;?> <span class="hidden-xs">| <?=$order->created_at;?> </span> </span>
        </div>
      </div>
      <div class="portlet-body">
        <?php echo $this->render('@supplier/views/order/_step.php', ['order' => $model]);?>

        <?php if (($model->isApprove() && $countComplain) || $model->isProcessing() || $model->isCompleted() ||  $model->isConfirmed()) :?>
        <div class="table-responsive">
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
                <td><?=$model->getGameTitle();?></td>
                <td><?=$model->quantity;?></td>
                <td><?=$model->doing;?></td>
                <td><?=number_format($model->price, 1);?></td>
                <td><?=number_format($model->total_price, 1);?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php endif;?>
        <div class="row">
          <div class="col-md-3">
            <div class="flex-container" style="text-align: center; background-color: #67809F;">
              <?php if ($model->isApprove()) {
                $loginStatus = 'Pending';
                if ($countComplain) $loginStatus = 'Pending Information';
              } else {
                $loginStatus = 'Login Successfully';
              }
              ?>
              <p style="padding-top: 8px; padding-bottom: 5px; flex-grow: 1; color: white;"> + Login status: </p> 
              <div style="color: #F1C40F; padding-top: 7px; padding-bottom: 5px; flex-grow: 2; background-color: white; font-weight: bold;"><?=$loginStatus;?></div>
            </div>
          </div>
          <?php if ($model->isProcessing() || $model->isCompleted() ||  $model->isConfirmed()) :?>
          <div class="col-md-9">
            <div class="flex-container" style="text-align: center; background-color: #67809F;">
              <p style="padding-top: 8px; flex-grow: 1; color: white"> + Cập nhật tiến độ: </p> 
              <?php if ($model->isProcessing()) : ?>
                <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md update-percent button-percent <?php if ($model->percent == 20) echo 'active';?>" data-value="20" href="<?=Url::to(['order/update-percent', 'id' => $model->id, 'percent' => 20]);?>"> 20% </a>
                <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md update-percent button-percent <?php if ($model->percent == 50) echo 'active';?>" data-value="50" href="<?=Url::to(['order/update-percent', 'id' => $model->id, 'percent' => 50]);?>"> 50% </a>
                <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md update-percent button-percent <?php if ($model->percent == 70) echo 'active';?>" data-value="70" href="<?=Url::to(['order/update-percent', 'id' => $model->id, 'percent' => 70]);?>"> 70% </a>
              <?php else : ?>
              <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md button-percent" disabled="true" data-value="20" href="javascript:;"> 20% </a>
                <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md button-percent" disabled="true" data-value="50" href="javascript:;"> 50% </a>
                <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md button-percent" disabled="true" data-value="70" href="javascript:;"> 70% </a>
              <?php endif;?>
              <div class="inline" style="margin: 0; flex-grow: 8; padding-top: 7px">
                <div class="progress progress-striped active" style="margin: 0;">
                  <div id="doing_unit_progress" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=$model->quantity;?>" aria-valuemin="0" aria-valuemax="<?=$model->quantity;?>" style="width: <?=$model->percent;?>%">
                      <span id='current_doing_unit' style="color: black"><?=$model->percent;?> %</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>
        </div>
        <div class="row">
          <div class="col-md-3 col-sm-6">
            <div class="portlet blue-hoki box" style="margin-bottom: 0">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-cogs"></i>Order Details
                </div>
              </div>
            </div>
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
            <div class="box-shadow">
              <a href="<?=Url::to(['order/move-to-processing', 'id' => $model->id]);?>" class="btn blue btn-block" data-toggle="modal" data-target="#go_processing"><i class="fa fa-angle-right"></i> >> Xác nhận login thành công << </a>
            </div>
            <?php endif;?>
            <?php if ($model->isProcessing()) : ?>
              <div class="box-shadow" style="margin-bottom: 10px">
                <button class="btn green btn-block" id="completedOrderBtn"> >> Chuyến tới trạng thái Completed << </button>
              </div>
            <?php endif;?>
            <?php if ($order->hasCancelRequest() && $model->isProcessing()) :?>
            <div class="box-shadow">
              <button type="button" class="btn btn-outline red btn-block" id="cancelOrderBtn"> >> Có yêu cầu hủy << </button>
            </div>
            <?php endif;?>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="dropzone dropzone-file-area" style="margin-bottom: 20px; padding: 5px;">
              <a role="button" class="btn green" id="uploadElement">Select photos</a>
              <!-- <p style="text-align: left; font-size: 1.3rem;">Có thể chọn tối đa 4 ảnh cùng lúc hoặc chuột trái <strong style="color: red">Select photo</strong> sau đó kéo thả từng ảnh từ ứng dụng chat hoặc kéo thả tối đa 4 ảnh từ thư mục máy tính vào cửa sổ Open.</p> -->
              <input type="file" id="uploadEvidence" name="uploadEvidence[]" style="display: none" multiple accept="image/*"/>
            </div>
            <div class="row" id="evidences">
                <?php echo $this->render('@supplier/views/order/_evidence.php', ['images' => $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE)]);?>
            </div>
            <div>
              <span class="label label-success"><i class="fa fa-lightbulb-o"></i></span>
              <span> Vui lòng tải đủ ảnh cần thiết và đúng ảnh account để tránh khiếu kiện từ khách hàng </span>
              <div class="form-group">
                <label class="form-label">Password: [Nếu có]</label>
                <input type="number" class="form-control" maxlength="8" placeholder="Tối đa 8 số" id="supplier_password">
              </div>
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
                <div class="timeline" id="complain-list" style="max-height: 500px; overflow-y: scroll;">
                </div>
                <div class="box-shadow inline">
                  <a href="<?=Url::to(['order/template', 'id' => $order->id]);?>"  data-target="#complain_template" class="btn green btn-default" data-toggle="modal"> >> Gửi tin nhắn theo mẫu << </a>
                </div>
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
        // location.reload();
        $('#complain_template').modal('hide');
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

<!-- Update quantity -->
<div class="modal fade" id="update_quantity" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" role="completed">Xác nhận hoàn thành đơn hàng</h4>
        <h4 class="modal-title" role="cancel">Xác nhận đồng ý/không đồng ý hủy đơn</h4>
      </div>
      <?= Html::beginForm(['order/move-to-completed', 'id' => $model->id], 'post', ['class' => 'form-horizontal form-row-seperated', 'id' => 'update-quantity-form', 'style' => 'padding-bottom: 20px']) ?>
      <div class="modal-body"> 
        <strong style="color: red">** ĐỒNG Ý HỦY:</strong><br/>
        <strong style="color: blue">** LƯU Ý:</strong>
        <ol>
          <li>Nhập chính xác số lượng gói đã hoàn thành vào ô "Số lượng gói đã hoàn thành" bên dưới.</li>
          <li>Đảm bảo đã tải đầy đủ ảnh, tên nhân vật trong ảnh trùng với tên nhân vật được cung cấp trong phần Order detail để tránh khiếu kiện từ khách hàng.</li>
        </ol>
        <div style="text-align: center;">
          <label class="control-label">Số lượng gói đã hoàn thành</label>
          <input type="text" class="form-control input-inline" name="doing" id="final_quantity" style="width: 100px">
           / <span class="help-inline" id="quantity"><?=$model->quantity;?></span>
          <button type="submit" class="btn btn-default help-inline">Xác nhận</button>
        </div>
      </div>
      <hr role="cancel"/>
      <div class="modal-body" role="cancel"> 
        <strong style="color: red">** KHÔNG ĐỒNG Ý HỦY:</strong><br/>
        <strong style="color: blue">** LƯU Ý:</strong>
        <ol>
          <li>Xin vui lòng cập nhật tiến độ trong phần "Updating Process".</li>
          <li>Đảm bảo rằng bạn sẽ hoàn thành đơn hàng trong thời gian sớm nhất.</li>
        </ol>
      </div>
      <div role="cancel" style="text-align: center;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Không đồng ý hủy</button>
      </div>
      <?= Html::endForm();?>
    </div>
  </div>
</div>
<?php
$updateQuantityJs = <<< JS
$('#cancelOrderBtn').on('click', function() {
  $('[role="completed"]').hide();
  $('[role="cancel"]').show();
  $('#update_quantity').modal('show');
});
$('#completedOrderBtn').on('click', function() {
  $('[role="completed"]').show();
  $('[role="cancel"]').hide();
  $('#update_quantity').modal('show');
});
$('.confirm_complete').click(function(){
  var _partial = parseFloat($('#final_quantity').val());
  var _quantity = parseFloat($('#quantity').text());
  if (isNaN(_partial) || _partial < 0 || _partial > _quantity) {
    bootbox.dialog({
      message: '<p class="text-center mb-0">Số lượng chưa hợp lệ</p>',
      buttons: {
        cancel: { label: 'OK' },
      },
    });
  } else {
    if (_partial < _quantity) {
      var title = 'Xác nhận một phần đơn hàng';
      var content = 'Bạn chưa nhập đủ số lượng game cho đơn hàng. Nếu hoàn tất lúc này, hệ thống sẽ ghi nhận số lượng hoàn thành của bạn là ' + _partial + ' / ' + _quantity + '. Bạn có chắc muốn thực hiện điều này?';
      bootbox.confirm({
        message: content,
        title: title,
        buttons: {
          cancel: { label: '<i class="fa fa-times"></i> Hủy' },
          confirm: { label: '<i class="fa fa-check"></i> Xác nhận' }
        },
        callback: function (result) {
          if (result === true) {
            $('form#update-quantity-form').submit();
          }
        }
      });
    } else if (_partial == _quantity) {
      var title = 'Chuyển tới trạng thái Completed';
      var content = 'Bạn có chắc chắn muốn chuyển đơn hàng này sang trạng thái "Completed" ?';
      bootbox.confirm({
        message: content,
        title: title,
        buttons: {
          cancel: { label: '<i class="fa fa-times"></i> Hủy' },
          confirm: { label: '<i class="fa fa-check"></i> Xác nhận' }
        },
        callback: function (result) {
          if (result === true) {
            $('form#update-quantity-form').submit();
          }
        }
      });
    }
  }
});

var moveCompletedForm = new AjaxFormSubmit({element: '#update-quantity-form'});
moveCompletedForm.success = function (data, form) {
  location.reload();
};
moveCompletedForm.error = function (errors) {
  bootbox.alert(errors);    
};
JS;
$this->registerJs($updateQuantityJs)
?>
<!-- End Update quantity --> 

<!-- Image upload --> 
<?php
$imageJs = <<< JS
var upload = new AjaxUploadFile({
  trigger_element: '#uploadElement', 
  file_element: '#uploadEvidence',
  file_options: {resize: '500xauto'},
  max: 2
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
// var uploadAfter = new AjaxUploadFile({
//   trigger_element: '#uploadElementAfter', 
//   file_element: '#uploadEvidenceAfter',
//   file_options: {resize: '500xauto'}
// });
// uploadAfter.callback = function(result) {
//   result.forEach(function(element) {
//     $.ajax({
//       url: '###ADD_EVIDENCE_AFTER_URL###',
//       type: 'POST',
//       dataType : 'json',
//       data: {'OrderFile[file_id]': element.id},
//       success: function (result, textStatus, jqXHR) {
//         if (result.status == false) {
//             alert('Error occur with #' + element.id);
//             return false;
//         } else {
//           $('#evidences_after').html(result.data.html);
//         }
//       },
//     });
//   });
// }
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
$('.update-percent').on('click', function(e){ 
  console.log('update percent click');
  $('.update-percent').removeClass('active');
  $(this).addClass('active');
});

$('.update-percent').ajax_action({
  confirm: false,
  callback: function(element, data) {
    var newpc = $(element).attr('data-value');
    $('#doing_unit_progress').css('width', newpc + '%');
    $('#doing_unit_progress span').html(newpc + ' %');
  }
});
JS;
$this->registerJs($progress);
?>       
<!-- end update percent -->


<!-- password -->
<?php
$passwordJs = <<< JS
$('#supplier_password').keyup(function (event) {
  if ($(this).val().length > 8) {
    $(this).val($(this).val().substring(0, 8));
  }
});
JS;
$this->registerJs($passwordJs);
?>       
<!-- end password -->


<?php
$complainRealtimeJs = <<< JS
Complains({
  id: '#complain-list',
  url: '###COMPALINS_URL###'
})
JS;
$complainListUrl = Url::to(['order-complain/list', 'id' => $order->id]);
$complainRealtimeJs = str_replace('###COMPALINS_URL###', $complainListUrl, $complainRealtimeJs);
$this->registerJs($complainRealtimeJs)
?>