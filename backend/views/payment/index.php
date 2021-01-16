<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\PaymentTransaction;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
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
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th>Mã GD Kcoin</th>
                <th>Mã đơn hàng</th>
                <th>Khách hàng</th>
                <th>Tài khoản gửi tiền</th>
                <th>Kcoin/Game</th>
                <th>Ngày tạo</th>
                <th>Chờ duyệt</th>
                <th>Cổng thanh toán</th>
                <th>Số tham chiếu</th>
                <th>Số Kcoin</th>
                <th>Phí giao dịch</th>
                <th>Tỉ lệ</th>
                <th>Nhận</th>
                <th>Đơn vị</th>
                <th>PIC</th>
                <th>Trạng thái</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="16">No data found</td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td><?=$model->id;?></td>
                <td><?=$model->object_ref == 'order' ? $model->object_key : '';?></td>
                <td><?=sprintf("%s (#%s)", $model->user->name, $model->user->id);?></td>
                <td></td>
                <td>
                  <?php $object = $model->object; ?>
                  <?php if ($model->object_ref == 'order') : ?>
                    <?=$object->game_title;?>
                  <?php elseif ($model->object_ref == 'wallet') : ?>
                    Kcoin
                  <?php endif;?>
                </td>
                <td><?=$model->created_at;?></td>
                <td>
                  <?php
                  $endTime = $model->approved_at ? strtotime($model->approved_at) : strtotime('now');
                  $startTime = strtotime($model->created_at);
                  echo round(($endTime - $startTime) / 60);
                  ?>
                </td>
                <td><?=$model->payment_method;?></td>
                <td><?=number_format($model->amount_usd, 1);?></td>
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