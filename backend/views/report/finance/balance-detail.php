<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datepicker\DateRangePicker;
use common\models\Order;
use common\models\UserWallet;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerCssFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js', ['depends' => '\backend\assets\AppAsset']);
?>

<style>
.hide-text {
    white-space: nowrap;
    width: 100%;
    max-width: 500px;
    text-overflow: ellipsis;
    overflow: hidden;
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
      <span>Thống kê & báo cáo</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê dòng tiền</span>
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
          <span class="caption-subject bold uppercase">Khách hàng <span style="color:red"><?=$user->name;?></span></span>
        </div>
        <div class="actions">
          <!-- <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a> -->
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/balance-detail', 'id' => $search->user_id]]);?>
            <div class="form-group col-md-4 col-lg-3">
              <label class="control-label">Ngày tạo</label>
              <div class="form-control" style="border: none; padding: 0">
                  <div id="reportrange" class="btn default">
                      <i class="fa fa-calendar"></i> &nbsp;
                      <span> </span>
                      <b class="fa fa-angle-down"></b>
                  </div>
              </div>
              <?=$form->field($search, 'start_date', [
                'template' => '{input}',
                'options' => ['tag' => false],
                'inputOptions' => ['id' => 'start_date', 'name' => 'start_date']
              ])->hiddenInput()->label(false);?>
              <?=$form->field($search, 'end_date', [
                'template' => '{input}',
                'options' => ['tag' => false],
                'inputOptions' => ['id' => 'end_date', 'name' => 'end_date']
              ])->hiddenInput()->label(false);?>
            </div>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 30%;"> Mã GD/Mã đơn hàng</th>
              <th style="width: 10%;"> Loại giao dịch </th>
              <th style="width: 25%;"> Thời gian hoàn thành </th>
              <th style="width: 10%;"> Kcoin </th>
              <th style="width: 10%;"> Số dư ban đầu</th>
              <th style="width: 10%;"> Số dư hiện tại</th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td>#<?=($pages->offset + $no + 1)?></td>
                <td style="vertical-align: middle;"><?=$model->description;?></td>
                <td style="vertical-align: middle;">
                  <?php if ($model->type == UserWallet::TYPE_INPUT) : ?>
                    <span class="label label-success">Nạp tiền</span>
                  <?php else : ?> 
                    <span class="label label-default">Rút tiền</span>
                  <?php endif; ?>
                </td>
                <td style="vertical-align: middle;"><?=$model->payment_at;?></td>
                <td style="vertical-align: middle;"><?=number_format($model->coin);?></td>
                <td style="vertical-align: middle;"><?=number_format($model->balance - $model->coin);?></td>
                <td style="vertical-align: middle;"><?=number_format($model->balance);?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Tổng cộng: <?=number_format($search->getCommand()->sum('coin'));?></td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
var dateFormat = 'YYYY/MM/DD';//MMMM D, YYYY
$('#reportrange').daterangepicker({
    opens: (App.isRTL() ? 'left' : 'right'),
    startDate: moment($('#start_date').val()),
    endDate: moment($('#end_date').val()),
    dateLimit: {
        days: 90
    },
    showDropdowns: true,
    showWeekNumbers: true,
    timePicker: false,
    timePickerIncrement: 1,
    timePicker12Hour: true,
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
        'Last 7 Days': [moment().subtract('days', 6), moment()],
        'Last 30 Days': [moment().subtract('days', 29), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
    },
    buttonClasses: ['btn'],
    applyClass: 'green',
    cancelClass: 'default',
    format: 'MM/DD/YYYY',
    separator: ' to ',
    locale: {
        applyLabel: 'Apply',
        fromLabel: 'From',
        toLabel: 'To',
        customRangeLabel: 'Custom Range',
        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        firstDay: 1
    }
  },
  function (start, end) {
      $('#reportrange span').html(start.format(dateFormat) + ' - ' + end.format(dateFormat));
      $('#start_date').val(start.format('YYYY-MM-DD'));
      $('#end_date').val(end.format('YYYY-MM-DD'));
  }
);
$('#reportrange span').html(moment($('#start_date').val()).format(dateFormat) + ' - ' + moment($('#end_date').val()).format(dateFormat));
JS;
$this->registerJs($script);
?>s