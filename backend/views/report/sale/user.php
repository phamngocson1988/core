<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datetimepicker\DateTimePicker;
use common\models\Order;
use yii\helpers\ArrayHelper;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

$userReports = [];
$dates = array_keys($models);
foreach ($models as $date => $records) {
  foreach ($records as $userId => $record) {
    $userReports[$userId]['name'] = $record['name'];
    $userReports[$userId]['dates'][$date]['quantity'] = $record['quantity'];
    $userReports[$userId]['dates'][$date]['total_price'] = $record['total_price'];
  }
}
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
      <span>Doanh số theo nhân viên</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Doanh số theo nhân viên</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Danh sách đơn hàng</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/sale-user']]);?>     
            <?=$form->field($search, 'start_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'start_date', 'id' => 'start_date']
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
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'end_date', 'id' => 'end_date']
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

            <div class='form-group col-md-4 col-lg-3'>
              <label class='control-label'>Thống kê theo:</label>
              <div class="clearfix">
                <div class="btn-group" data-toggle="buttons">
                  <label class="btn red <?=($search->period == 'day') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="day" <?=($search->period == 'day') ? 'checked="checked"' : '';?> > Ngày </label>
                  <label class="btn red <?=($search->period == 'week') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="week" <?=($search->period == 'week') ? 'checked="checked"' : '';?> > Tuần </label>
                  <label class="btn red <?=($search->period == 'month') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="month" <?=($search->period == 'month') ? 'checked="checked"' : '';?> > Tháng </label>
                  <label class="btn red <?=($search->period == 'quarter') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="quarter" <?=($search->period == 'quarter') ? 'checked="checked"' : '';?> > Quý </label>
                </div>
              </div>
            </div>	
            
            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th style="vertical-align: middle; text-align: center" rowspan="2"> STT </th>
                  <th style="vertical-align: middle; text-align: center" rowspan="2"> Tên nhân viên </th>
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
                  <th style="vertical-align: middle; text-align: center">Số gói</th>
                  <th style="vertical-align: middle; text-align: center">Số coin</th>
                  <?php else : ?>
                  <?php foreach ($models as $date => $records) :?>
                  <th style="vertical-align: middle; text-align: center">Số gói</th>
                  <th style="vertical-align: middle; text-align: center">Số coin</th>
                  <?php endforeach;?>
                  <?php endif;?>
                </tr>
              </thead>
              <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="<?=($search->period == 'day') ? 4 : 2;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach (array_values($userReports) as $no => $record):?>
                <tr>
                  <td style="vertical-align: middle; text-align: center"><?=++$no;?></td>
                  <td style="vertical-align: middle; text-align: left; padding-left: 8px"><?=$record['name'];?></td>
                  <?php if ($search->period == 'day') : ?>
                  <?php $reportData = $record['dates']; ?>
                  <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'quantity')), 1);?></td>
                  <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'total_price')), 1);?></td>
                  <?php else : ?>
                  <?php foreach ($dates as $date): ?>
                  <?php $reportData = ArrayHelper::getValue($record['dates'], $date, ['quantity' => 0, 'total_price' => 0]);?>
                  <td style="vertical-align: middle; text-align: center"><?=round($reportData['quantity'], 1);?></td>
                  <td style="vertical-align: middle; text-align: center"><?=round($reportData['total_price'], 1);?></td>
                  <?php endforeach;?>
                  <?php endif;?>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>