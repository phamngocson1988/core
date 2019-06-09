<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datetimepicker\DateTimePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$gameReports = [];
$dates = array_keys($models);
foreach ($models as $date => $records) {
  foreach ($records as $gameId => $game) {
    $gameReports[$gameId]['game_title'] = $game['game_title'];
    $gameReports[$gameId]['dates'][$date]['game_pack'] = $game['game_pack'];
    $gameReports[$gameId]['dates'][$date]['total_price'] = $game['total_price'];
  }

}
// print_r($models);die;
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
      <span>Thống kê bán hàng</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Doanh số theo game</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Doanh số theo game</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Biểu đồ</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/sale-game']]);?>
        <div class="row">
          <?=$form->field($search, 'limit', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'limit', 'id' => 'limit']
          ])->dropDownList($search->getLimitOptions())->label('Tùy chọn thống kê');?>

          <?=$form->field($search, 'game_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
          ])->widget(kartik\select2\Select2::classname(), [
            'initValueText' => ($search->game_id) ? sprintf("%s", $search->getGame()->title) : '',
            'options' => ['class' => 'form-control', 'name' => 'game_id'],
            'pluginOptions' => [
              'placeholder' => 'Chọn game',
              'allowClear' => true,
              'minimumInputLength' => 3,
              'ajax' => [
                  'url' => Url::to(['game/suggestion']),
                  'dataType' => 'json',
                  'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
              ]
            ]
          ])->label('Tên game')?>

          <?=$form->field($search, 'start_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'start_date']
          ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii',
                'minuteStep' => 1,
              ]
          ])->label('Ngày tạo từ');?>

          <?=$form->field($search, 'end_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'end_date']
          ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd hh:ii',
                  'todayBtn' => true,
                  'minuteStep' => 1,
              ]
          ])->label('Ngày tạo đến');?>
        
          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="row">
          <div class="col-md-12">
            <?php Pjax::begin(); ?>
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead style="background-color: #d0d0cf">
                <tr>
                  <th style="vertical-align: middle; text-align: center" rowspan="2"> STT </th>
                  <th style="vertical-align: middle; text-align: center" rowspan="2"> Tên game </th>
                  <?php if ($search->period == 'day') : ?>
                  <th style="vertical-align: middle; text-align: center" colspan="2">Thống kê theo ngày</th>
                  <?php else : ?>
                  <?php foreach ($models as $date => $records) :?>
                  <th style="vertical-align: middle; text-align: center" colspan="2"><?=$search->getLabelByPeriod($date);?></th>
                  <?php endforeach;?>
                  <?php endif;?>
                </tr>
                <tr>
                  <?php if ($search->period == 'day') : ?>
                  <th style="vertical-align: middle; text-align: center">Số lượng gói</th>
                  <th style="vertical-align: middle; text-align: center">Số coin</th>
                  <?php else : ?>
                  <?php foreach ($models as $date => $records) :?>
                  <th style="vertical-align: middle; text-align: center">Số lượng gói</th>
                  <th style="vertical-align: middle; text-align: center">Số coin</th>
                  <?php endforeach;?>
                  <?php endif;?>
                </tr>
              </thead>
              <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="<?=count($records) + 2;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach (array_values($gameReports) as $no => $game): ?>
                <tr>
                    <td style="vertical-align: middle; text-align: center"><?=++$no;?></td>
                    <td style="vertical-align: middle; text-align: left; padding-left: 8px"><?=$game['game_title'];?></td>
                    <?php if ($search->period == 'day') : ?>
                    <?php 
                    $reportData = $game['dates'];
                    $completedCount = array_sum(array_column($reportData, 'completed_count'));
                    $penddingCount = array_sum(array_column($reportData, 'pendding_count'));
                    $totalProcessTime = array_sum(array_column($reportData, 'total_process_time'));
                    $rate = (!$completedCount) ? 0 : $completedCount / ($completedCount + $penddingCount) * 100;
                    $avarageTime = (!$completedCount) ? 0 : $totalProcessTime / ($completedCount * 60); //mins
                    ?>
                    <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'game_pack')), 1);?></td>
                    <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'total_price')), 1);?></td>
                    <?php else : ?>
                    <?php foreach ($dates as $date): ?>
                    <?php $reportData = ArrayHelper::getValue($game['dates'], $date, ['game_pack' => 0, 'completed_rate' => 0, 'avarage_time' => 0]) ;?>
                    ?>
                    <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'game_pack')), 1);?></td>
                    <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'total_price')), 1);?></td>
                    <?php endforeach;?>
                    <?php endif;?>
                  </tr>
                <?php endforeach;?>
              </tbody>
            </table>
            <?php Pjax::end(); ?>
          </div>
        </div>
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

var sendForm = new AjaxFormSubmit({element: '.assign-form'});
sendForm.success = function (data, form) {
  location.reload();
}
JS;
$this->registerJs($script);
?>