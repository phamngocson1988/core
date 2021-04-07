<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use common\models\PaymentReality;
use common\components\helpers\TimeHelper;

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
      <span>Giao dịch nạp tiền</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Giao dịch nạp tiền</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Giao dịch nạp tiền</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>
            <a class="btn green" href="<?=Url::to(['payment-reality/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['payment-reality/index']]);?>
          <div class="row margin-bottom-10">
            <?=$form->field($search, 'id', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-6 col-md-2 col-lg-1 col-xl-1'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'id']
            ])->textInput()->label('Mã nhận tiền');?>

            <?=$form->field($search, 'object_key', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-6 col-md-2 col-lg-1 col-xl-1'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'object_key']
            ])->textInput()->label('Mã đơn hàng');?>

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

            <?=$form->field($search, 'payer', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-6 col-md-2 col-lg-1 col-xl-1'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'payer']
            ])->textInput()->label('TK người gửi');?>

            <?=$form->field($search, 'payment_id', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'payment_id']
            ])->textInput()->label('Mã tham chiếu người nhận');?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'status']
            ])->dropDownList($search->fetchStatus(), ['prompt' => 'Chọn trạng thái'])->label('Trạng thái');?>
          </div>
          <div class="row margin-bottom-10">
            <?=$form->field($search, 'paygate', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'paygate']
            ])->dropDownList($search->fetchPaygate(), ['prompt' => 'Chọn cổng thanh toán'])->label('Lọc theo cổng thanh toán');?>

            <?=$form->field($search, 'date_type', [
              'options' => ['class' => 'form-group col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'date_type']
            ])->dropDownList([
              'created_at' => 'Ngày cập nhật',
              'payment_time' => 'Ngày nhận hóa đơn',
              'object_created_at' => 'Ngày tạo đơn',
            ], ['prompt' => 'Chọn mốc thời gian'])->label('Lọc theo mốc thời gian');?>

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
                <th col-tag="id">Mã nhận tiền</th>
                <th col-tag="object_key">Mã đơn hàng</th>
                <th col-tag="customer">Khách hàng</th>
                <th col-tag="object_name">Kcoin/Shop Game</th>
                <th col-tag="object_created_at">Ngày tạo đơn</th>
                <th col-tag="created_at">Ngày cập nhật</th>
                <th col-tag="confirmed_at">Ngày duyệt</th>
                <th col-tag="payment_time">Ngày nhận hoá đơn</th>
                <th col-tag="waiting_time">TG chờ cập nhật hoá đơn</th>
                <th col-tag="confirmed_time">TG chờ duyệt</th>
                <th col-tag="paygate">Cổng thanh toán</th>
                <th col-tag="payer">TK người gửi</th>
                <th col-tag="payment_id">Mã tham chiếu người nhận</th>
                <th col-tag="payment_note">Ghi chú từ khách hàng</th>
                <th col-tag="kingcoin">Thực nhận (Kcoin)</th>
                <th col-tag="created_by">Người nhập</th>
                <th col-tag="confirmed_by">Người duyệt</th>
                <th col-tag="object_file">Hoá đơn người gửi</th>
                <th col-tag="file">Hoá đơn người nhận</th>
                <th col-tag="payment_note">Ghi chú nhận tiền</th>
                <th col-tag="status">Trạng thái</th>
                <th col-tag="action">Tác vụ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="22" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <?php $object = $model->object;?>
              <tr>
                <td col-tag="id"><?=$model->getId();?></td>
                <td col-tag="object_key">
                <?php 
                if ($model->isPending()) {
                  echo '--';
                } elseif ($model->isClaimed()) {
                  echo $model->getObjectKey();
                } elseif ($model->isDeleted()) {
                  echo $model->object_key;
                }
                ?>
                </td>
                <td col-tag="customer">
                <?php
                $user = $model->user;
                echo $user ? $user->name : '--';
                ?>
                </td>
                <td col-tag="object_name">
                <?php 
                if ($model->object_name == 'wallet') {
                  echo 'Kcoin';
                } elseif ($model->object_name == 'order') {
                  echo $object ? $object->game_title : 'Không tìm thấy đơn hàng tương ứng';
                } else {
                  echo '--';
                }
                ?>
                </td>
                <td col-tag="object_created_at"><?=$object ? $object->created_at : '--';?></td>
                <td col-tag="created_at"><?=$model->created_at;?></td>
                <td col-tag="confirmed_at"><?=$model->confirmed_at ? $model->confirmed_at : '--';?></td>
                <td col-tag="payment_time"><?=$model->payment_time;?></td>
                <td col-tag="waiting_time">
                <?php 
                if ($model->payment_time) {
                  echo round(TimeHelper::timeDiff($model->payment_time, $model->created_at, 'minute'));
                } else {
                  echo '--';
                }
                ?>
                </td>
                <td col-tag="confirmed_time">
                <?php
                if ($model->isPending()) {
                  echo round(TimeHelper::timeDiff($model->created_at, $now, 'minute'));
                } elseif ($model->isClaimed()) {
                  echo round(TimeHelper::timeDiff($model->created_at, $model->confirmed_at, 'minute'));
                } else {
                  echo '--';
                }
                ?>
                </td>
                <td col-tag="paygate"><?=$model->paygate;?></td>
                <td col-tag="payer"><?=$model->payer;?></td>
                <td col-tag="payment_id"><?=$model->payment_id;?></td>
                <td col-tag="payment_note"><?=nl2br($model->payment_note);?></td>
                <td col-tag="kingcoin"><?=$model->kingcoin;?></td>
                <td col-tag="created_by"><?=$model->payment_type === PaymentReality::PAYMENTTYPE_OFFLINE ? $model->creator->name : 'Cổng thanh toán ONLINE';?></td>
                <td col-tag="confirmed_by">
                <?php
                if ($model->payment_type === PaymentReality::PAYMENTTYPE_OFFLINE) {
                  if ($model->isClaimed()) echo $model->confirmer->name;
                  else echo '--';
                } else { 
                  echo 'Cổng thanh toán ONLINE';
                }
                ?>
                </td>
                <td col-tag="object_evidence"><?=($object && $object->evidence) ? Html::a('Xem', $object->evidence, ['class' => 'fancybox']) : '--';?></td>
                <td col-tag="evidence"><?=$model->evidence ? Html::a('Xem', $model->evidence, ['class' => 'fancybox', 'data-fancybox' => 'group' . $model->getId()]) : '--';?></td>
                <td col-tag="payment_note"><?=nl2br($model->note);?></td>
                <td col-tag="status">
                  <?php if ($model->isPending()) : ?>
                  <span class="label label-info">Pending</span>
                  <?php elseif ($model->isClaimed()) : ?>
                  <span class="label label-default">Claimed</span>
                  <?php endif;?>
                </td>
                <td col-tag="action">
                <?php if ($model->isPending()) :?>
                <a class="btn btn-xs red tooltips delete-payment-action" href="<?=Url::to(['payment-reality/delete', 'id' => $model->id]);?>" data-container="body" data-original-title="Cho vào thùng rác"><i class="fa fa-trash"></i></a>
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
<?php
$script = <<< JS
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
      toastr(errors);
  },
});
$(".fancybox").fancybox();
JS;
$this->registerJs($script);
?>