<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datepicker\DateRangePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;
use dosamigos\chartjs\ChartJs;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerCssFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js', ['depends' => '\backend\assets\AppAsset']);

$orderTeamIds = Yii::$app->authManager->getUserIdsByRole('handler');
$adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
$orderTeamIds = array_merge($orderTeamIds, $adminTeamIds);
$orderTeamIds = array_unique($orderTeamIds);
$orderTeamObjects = User::findAll($orderTeamIds);
$orderTeam = ArrayHelper::map($orderTeamObjects, 'id', 'email');

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
      <span>Thống kê thực hiện đơn hàng</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Theo nhân viên</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo nhân viên</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê thực hiện đơn hàng theo nhân viên</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/process-user']]);?>
        <div class="row">
          <?=$form->field($search, 'handler_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'handler_id']
          ])->dropDownList($search->fetchUsers(), ['prompt' => 'Tìm theo nhân viên'])->label('Tên nhân viên');?>
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
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <div class="row">
          <div class="col-md-6">
            <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
              <thead>
                <tr>
                  <th style="width: 30%;"> Nhân viên </th>
                  <th style="width: 20%;"> Số lượng gói </th>
                  <th style="width: 20%;"> Tỷ lệ hoàn thành </th>
                  <th style="width: 30%;"> Thời gian xử lý trung bình </th>
                </tr>
              </thead>
              <tbody>
                  <?php if (!$models) :?>
                  <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
                  <?php endif;?>
                  <?php foreach ($models as $no => $model) :?>
                  <tr>
                    <td style="vertical-align: middle;"><?=$model['name'];?></td>
                    <td style="vertical-align: middle;"><?=round($model['game_pack'], 1);?></td>
                    <td style="vertical-align: middle;"><?=round($model['completed_rate']) . '%';?></td>
                    <td style="vertical-align: middle;"><?=round($model['avarage_time']) . ' (minutes)';?></td>
                  </tr>
                  <?php endforeach;?>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <?php
            $game_packs = array_map(function($item) { 
              return round($item['game_pack'], 1);
            }, $models);
            $completed_rates = array_map(function($item) { 
              return round($item['completed_rate'], 1);
            }, $models);
            $avarage_times = array_map(function($item) { 
              return round($item['avarage_time']);
            }, $models);
            $labels = array_column($models, 'name');
            $datasets = [
                [
                    'label' => "Thời gian xử lý trung bình",
                    'backgroundColor' => "rgba(54,198,211,0.2)",
                    'borderColor' => "rgba(54,198,211,1)",
                    'pointBackgroundColor' => "rgba(54,198,211,1)",
                    'pointBorderColor' => "#fff",
                    'pointHoverBackgroundColor' => "#fff",
                    'pointHoverBorderColor' => "rgba(54,198,211,1)",
                    'data' => array_values($game_packs)
                ],
                [
                    'label' => "Tỷ lệ hoàn thành",
                    'backgroundColor' => "rgba(255,99,132,0.2)",
                    'borderColor' => "rgba(255,99,132,1)",
                    'pointBackgroundColor' => "rgba(255,99,132,1)",
                    'pointBorderColor' => "#fff",
                    'pointHoverBackgroundColor' => "#fff",
                    'pointHoverBorderColor' => "rgba(255,99,132,1)",
                    'data' => array_values($completed_rates)
                ],
                [
                    'label' => "Số lượng gói",
                    'backgroundColor' => "rgba(179,181,198,0.2)",
                    'borderColor' => "rgba(179,181,198,1)",
                    'pointBackgroundColor' => "rgba(179,181,198,1)",
                    'pointBorderColor' => "#fff",
                    'pointHoverBackgroundColor' => "#fff",
                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
                    'data' => array_values($avarage_times)
                ],
                
            ];
            ?>
            <?= ChartJs::widget([
              'type' => 'bar',
              'options' => [
                  'height' => 200,
                  'width' => 400
              ],
              'data' => [
                  'labels' => $labels,
                  'datasets' => $datasets
              ]
          ]);
          ?>
          </div>
        </div>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".ajax-link").ajax_action({
  method: 'POST',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});

// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa đơn hàng này không?',
  callback: function(data) {
    location.reload();
  },
});

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

var sendForm = new AjaxFormSubmit({element: '.assign-form'});
sendForm.success = function (data, form) {
  location.reload();
}
JS;
$this->registerJs($script);
?>