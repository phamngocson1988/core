<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use common\models\Payment;
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
            <a class="btn green" href="<?=Url::to(['payment/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
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
                <th col-tag="status">Trạng thái</th>
                <th col-tag="object_file">Hoá đơn người gửi</th>
                <th col-tag="file">Hoá đơn người nhận</th>
                <th col-tag="payment_note">Ghi chú nhận tiền</th>
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
                  echo round(TimeHelper::timeDiff($model->created_at, $model->claimed_at, 'minute'));
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
                <td col-tag="created_by"><?=$model->payment_type === Payment::PAYMENTTYPE_OFFLINE ? $model->creator->name : 'Cổng thanh toán ONLINE';?></td>
                <td col-tag="confirmed_by">
                <?php
                if ($model->payment_type === Payment::PAYMENTTYPE_OFFLINE) {
                  if ($model->isClaimed()) echo $model->confirmer->name;
                  else echo '--';
                } else { 
                  echo 'Cổng thanh toán ONLINE';
                }
                ?>
                </td>
                <td col-tag="status"><?=$model->status;?></td>
                <td col-tag="object_file">--</td>
                <td col-tag="file"><?=$model->file_id ? 'Bill nhận' : '--';?></td>
                <td col-tag="payment_note"><?=nl2br($model->note);?></td>
                <td col-tag="action">
                <?php if ($model->isPending()) :?>
                <a class="btn btn-xs red tooltips delete-payment-action" href="<?=Url::to(['payment/delete', 'id' => $model->id]);?>" data-container="body" data-original-title="Cho vào thùng rác"><i class="fa fa-trash"></i></a>
                <?php endif;?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
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
JS;
$this->registerJs($script);
?>