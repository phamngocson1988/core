<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datepicker\DateRangePicker;
use common\models\Order;

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
      <span>Thống kê theo đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thống kê theo đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Thống kê theo đơn hàng</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/index']]);?>     
            <?php $customer = $search->getCustomer();?>
            <?=$form->field($search, 'customer_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->customer_id) ? sprintf("%s - %s", $customer->username, $customer->email) : '',
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

            <?php if (Yii::$app->user->can('admin')) :?>
            <?php $saler = $search->getSaler();?>
            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->saler_id) ? sprintf("%s - %s", $saler->username, $saler->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'saler_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên sale',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên sale')?>

            <?php $orderTeam = $search->getOrderteam();?>
            <?=$form->field($search, 'orderteam_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($orderTeam) ? sprintf("%s - %s", $orderTeam->username, $orderTeam->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'orderteam_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên đơn hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên đơn hàng')?>
            <?php elseif (Yii::$app->user->can('saler')):?>
              <?=$form->field($search, 'saler_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'saler_id']
              ])->hiddenInput()->label(false);?>
            <?php elseif (Yii::$app->user->can('orderteam')):?>
              <?=$form->field($search, 'orderteam_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'orderteam_id']
              ])->hiddenInput()->label(false);?>
            <?php endif;?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-2'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'status[]']
            ])->dropDownList($search->getStatus())->label('Trạng thái');?>

            <div class="form-group col-md-2">
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

            <div class="form-group col-md-2">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 20%;"> Tên khách hàng </th>
              <th style="width: 20%;" data-field="created_at" data-sortable="true" data-sort-name="created_at"> Ngày tạo </th>
              <th style="width: 5%;"> Tổng Coin </th>
              <th style="width: 15%;"> Saler </th>
              <th style="width: 15%;"> Order Team </th>
              <th style="width: 10%;"> <?=Yii::t('app', 'status');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td style="vertical-align: middle;"><a href="<?=Url::to(['report/view', 'id' => $model->id]);?>">Order #<?=$model->id;?></a></td>
                <td style="vertical-align: middle;"><?=$model->customer_name;?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                <td style="vertical-align: middle;">$<?=$model->total_price;?></td>
                <td style="vertical-align: middle;"><?=($model->saler) ? $model->saler->name : '';?></td>
                <td style="vertical-align: middle;"><?=($model->orderteam) ? $model->orderteam->name : '';?></td>
                <td style="vertical-align: middle;"><?=$model->status;?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td style="vertical-align: middle;" colspan="3">Tổng đơn hàng: <?=number_format($search->getCommand()->count());?></td>
              <td style="vertical-align: middle;" colspan="4">Tổng King Coin: <?=number_format($search->getCommand()->sum('total_price'));?></td>
              </td>
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
?>