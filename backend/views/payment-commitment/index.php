<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use common\models\PaymentCommitment;
use common\components\helpers\TimeHelper;
use common\components\helpers\StringHelper;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

$now = date('Y-m-d H:i:s');
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Lịch sử giao dịch</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Lịch sử giao dịch</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Lịch sử giao dịch</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['payment-commitment/index']]);?>
          <div class="row margin-bottom-10">
            <?=$form->field($search, 'object_key', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-6 col-md-2 col-lg-1 col-xl-1'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'object_key']
            ])->textInput()->label('Mã đơn hàng');?>

            <?=$form->field($search, 'payment_id', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'payment_id']
            ])->textInput()->label('Mã nhận tiền');?>

            <?=$form->field($search, 'customer_id', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->customer_id && $search->getCustomer()) ? sprintf("%s - %s", $search->getCustomer()->username, $search->getCustomer()->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'customer_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn khách hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Khách hàng')?>

            <?=$form->field($search, 'paygate', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'paygate']
            ])->dropDownList($search->fetchPaygate(), ['prompt' => 'Chọn cổng thanh toán'])->label('Lọc theo cổng thanh toán');?>

            <?= $form->field($search, 'start_date', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'start_date']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Ngày tạo từ');?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'status']
            ])->dropDownList($search->fetchStatus(), ['prompt' => 'Chọn trạng thái'])->label('Trạng thái');?>

            <?=$form->field($search, 'end_date', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'end_date']
            ])->widget(DateTimePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd hh:59',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d H:i'),
                  'minView' => '1'
                ],
            ])->label('Ngày tạo đến');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable" id="payment-table">
            <thead>
              <tr>
                <th col-tag="object_key">Mã đơn hàng</th>
                <th col-tag="payment_reality_id">Mã nhận tiền</th>
                <th col-tag="customer">Khách hàng</th>
                <th col-tag="object_name">Kcoin/Shop Game</th>
                <th col-tag="object_created_at">Ngày tạo đơn</th>
                <th col-tag="confirmed_time">TG duyệt</th>
                <th col-tag="payment_id">Mã tham chiếu người gửi</th>
                <th col-tag="voucher_code">Mã khuyến mãi</th>
                <th col-tag="voucher_value">Khuyến mãi (Kcoin)</th>
                <th col-tag="paygate">Cổng thanh toán</th>
                <th col-tag="amount">Giá trị đơn hàng (Kcoin)</th>
                <th col-tag="fee">Phí chuyển tiền (Kcoin)</th>
                <th col-tag="kingcoin_reality">Thực nhận (Kcoin)</th>
                <th col-tag="kingcoin_difference">Chênh lệch (Kcoin)</th>
                <th col-tag="exchange_rate">Tỷ giá</th>
                <th col-tag="currency">Tiền tệ</th>
                <th col-tag="evidence">Hóa đơn</th>
                <th col-tag="confirmed_by">Người duyệt</th>
                <th col-tag="status">Trạng thái</th>
                <th col-tag="action">Tác vụ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="18" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <?php $object = $model->object;?>
              <?php $reality = $model->reality;?>
              <?php  $user = $model->user;?>
              <tr>
                <td col-tag="object_key"><?=$object->getId();?></td>
                <td col-tag="payment_reality_id"><?=$reality ? $reality->getId() : '--';?></td>
                <td col-tag="customer"><?=$user ? $user->name : '--';?></td>
                <td col-tag="object_name">
                <?php 
                if ($model->isObjectWallet()) {
                  echo 'Kcoin';
                } elseif ($model->isObjectOrder()) {
                  echo $object->game_title;
                } else {
                  echo '--';
                }
                ?>
                </td>
                <td col-tag="object_created_at"><?=$object->created_at;?></td>
                <td col-tag="confirmed_time">
                <?php
                if ($model->isPending()) {
                  echo round(TimeHelper::timeDiff($model->created_at, $now, 'minute'));
                } elseif ($model->isApproved()) {
                  echo round(TimeHelper::timeDiff($model->created_at, $model->confirmed_at, 'minute'));
                } else {
                  echo '--';
                }
                ?>
                </td>
                <td col-tag="payment_id"><?=$model->payment_id;?></td>
                <td col-tag="voucher_code"></td>
                <td col-tag="voucher_value"></td>
                <td col-tag="paygate"><?=$model->paygate;?></td>
                <td col-tag="amount"><?=StringHelper::numberFormat($model->amount / $model->exchange_rate, 2);?></td>
                <td col-tag="fee"><?=StringHelper::numberFormat($model->fee / $model->exchange_rate, 2);?></td>
                <td col-tag="kingcoin_reality"><?=$reality ? $reality->kingcoin : '--';?></td>
                <td col-tag="kingcoin_difference"><?=$reality ? StringHelper::numberFormat($reality->kingcoin - $model->kingcoin, 2) : '--';?></td>
                <td col-tag="exchange_rate"><?=$model->exchange_rate;?></td>
                <td col-tag="currency"><?=$model->currency;?></td>
                <td col-tag="evidence"><?=$model->evidence ? Html::a('Xem', $model->evidence, ['class' => 'fancybox']) : '--';?></td>
                <td col-tag="confirmed_by">
                <?php
                if ($model->payment_type === PaymentCommitment::PAYMENTTYPE_OFFLINE) {
                  if ($model->isApproved() && $model->confirmer) echo $model->confirmer->name;
                  else echo '--';
                } else { 
                  echo 'Cổng thanh toán ONLINE';
                }
                ?>
                </td>
                <td col-tag="status">
                  <?php if ($model->isPending()) : ?>
                  <span class="label label-info">Pending</span>
                  <?php elseif ($model->isApproved()) : ?>
                  <span class="label label-default">Approved</span>
                  <?php endif;?>

                  <?php if ($model->isObjectOrder() && $object->hasCancelRequest()): ?>
                    <span class="label label-danger">Có yêu cầu hủy</span>
                  <?php endif;?>
                </td>
                <td col-tag="action">
                <?php if ($model->isPending()) :?>
                  <a href='#approve-commitment-modal-<?=$model->id;?>' class="btn btn-xs blue tooltips" data-container="body" data-original-title="Xác nhận đã thanh toán" data-toggle="modal" ><i class="fa fa-exchange"></i></a>
                  <?php if (Yii::$app->user->can('sale_manager')) :?>
                  <a class="btn btn-xs red tooltips delete-payment-action" href="<?=Url::to(['payment-commitment/delete', 'id' => $model->id]);?>" data-container="body" data-original-title="Cho vào thùng rác"><i class="fa fa-trash"></i></a>
                  <?php endif;?>
                <?php endif;?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php foreach ($models as $model) :?>
<?php if ($model->isPending()) :?>
<div class="modal fade" id="approve-commitment-modal-<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">DUYỆT ĐƠN HÀNG</h4>
      </div>
      <?php $form = ActiveForm::begin([
        'options' => ['class' => 'approve-commitment-form'], 
        'action' => Url::to(['payment-commitment/approve', 'id' => $model->id])
      ]);?>
      <div class="modal-body"> 
        <div class="row">
          <div class="col-md-12">
              <table class="table table-striped table-bordered table-hover table-checkable">
                <tbody>
                  <tr>
                    <td class="left pl-5 pl-5">Ngày cập nhật:</td>
                    <td class="created_at"></td>
                  </tr>
                  <tr>
                    <td class="left pl-5">Cổng tdanh toán:</td>
                    <td class="paygate"></td>
                  </tr>
                  <tr>
                    <td class="left pl-5">TK người gửi:</td>
                    <td class="payer"></td>
                  </tr>
                  <tr>
                    <td class="left pl-5">TG nhận hoá đơn:</td>
                    <td class="payment_time"></td>
                  </tr>
                  <tr>  
                    <td class="left pl-5">Mã tdam Chiếu người nhận:</td>
                    <td class="payment_id"></td>
                  </tr>
                  <tr> 
                    <td class="left pl-5">Thực Nhận (Kcoin):</td>
                    <td class="kingcoin"></td>
                  </tr>
                  <tr>
                    <td class="left pl-5">Mã đơn hàng đang duyệt:</td>
                    <td><?=$model->object->getId();?></td>
                  </tr>
                  <tr>  
                    <td class="left pl-5">Khách hàng:</td>
                    <td><?=$model->user->name;?></td>
                  </tr>
                  <tr>
                    <td class="left pl-5">Cần tdanh toán (Kcoin)</td>
                    <td kingcoin="<?=$model->kingcoin;?>" class="commitment-coin"><?=StringHelper::numberFormat($model->kingcoin, 2);?></td>
                  </tr>
                </tbody>
              </table>
          </div>
        </div>
        <div class="row alert hidden" style="color: red">
          <strong>**CẢNH BÁO:</strong><br>
          Chênh lệch Kcoin giữa giao dịch và mã nhân tiền là <span class="variance">0</span> Kcoin
        </div>
        <div class="row">
          <div class="col-md-12">
            <?=$form->field($approveForm, 'payment_reality_id', [
              'options' => ['class' => 'form-group col-md-12 col-lg-12'],
              'inputOptions' => ['class' => 'form-control payment_reality_id']
            ])->dropdownList($paymentRealities, ['prompt' => 'Chọn mã nhận tiền'])->label('Chọn mã nhận tiền');?>
          </div>
          <div class="col-md-12">
            <?=$form->field($approveForm, 'note', [
              'options' => ['class' => 'form-group col-md-12 col-lg-12'],
            ])->textArea()->label('Ghi chú duyệt đơn hàng');?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Xác nhận</button>
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
      <?php ActiveForm::end()?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php endif;?>
<?php endforeach;?>
<?php
$realityViewUrl = Url::to(['payment-reality/ajax-view']);
$script = <<< JS
var variance = parseFloat($approveForm->variance);
$(".delete-payment-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn xóa giao dịch này?',
  callback: function(eletement, data) {
    toastr.success('Bạn đã xoá thành công');
    setTimeout(() => {  
      location.reload();
    }, 1000);
  },
  error: function(element, errors) {
    toastr.error(errors);
  },
});

$(document).on('submit', 'body .approve-commitment-form', function(e) {
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
       toastr.error(result.errors);
      else 
        location.reload();
    },
  });
  return false;
});

const realityMapFields = ['created_at', 'paygate', 'payer', 'payment_time', 'payment_id', 'kingcoin'];
$('.payment_reality_id').on('change', function() {
  $(".alert").addClass('hidden');
  var id = $(this).val();
  var that = this;
  $.ajax({
    url: '$realityViewUrl' + '?id=' + id,
    type: 'GET',
    dataType : 'json',
    success: function (result, textStatus, jqXHR) {
      if (result) {
        let form = $(that).closest('form');
        realityMapFields.forEach(element => {
          $(that).closest('form').find('.' + element).html(result[element]);
        });
        var commitmentCoin = parseFloat($(that).closest('form').find('.commitment-coin').attr('kingcoin'));
        var realityCoin = parseFloat(result.kingcoin);
        console.log('commitment - reality', commitmentCoin, realityCoin, variance);
        let diff = commitmentCoin - realityCoin;
        diff = Math.abs(parseFloat(diff.toFixed(2)));
        if (diff >= variance) {
          console.log('show alert');
          form.find('.variance').html(diff);
          $(".alert").removeClass('hidden');
        }
      } else {
        toastr.error('Không tìm thấy thông tin Mã nhận tiền');
      }
    },
  });
});

$(".fancybox").fancybox();
JS;
$this->registerJs($script);
?>