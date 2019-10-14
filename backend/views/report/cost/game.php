<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;
use dosamigos\chartjs\ChartJs;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

$settings = Yii::$app->settings;
$rate = (float)$settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 22000);

$gameReports = [];
$dates = array_keys($models);
foreach ($models as $date => $records) {
  foreach ($records as $gameId => $game) {
    $gameReports[$gameId]['game_title'] = $game['game_title'];
    $gameReports[$gameId]['dates'][$date]['quantity'] = $game['quantity'];
    $gameReports[$gameId]['dates'][$date]['total_price'] = $game['total_price'];
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
      <span>Thống kê chi phí lợi nhuận</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Theo game</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chi phí lợi nhuận theo game</h1>
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
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/cost-game']]);?>
        <div class="row">
          <?=$form->field($search, 'limit', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'limit', 'id' => 'limit']
          ])->dropDownList($search->getLimitOptions())->label('Tùy chọn thống kê');?>

          <?=$form->field($search, 'game_ids', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
          ])->widget(kartik\select2\Select2::classname(), [
            'data' => $search->fetchGames(),
            'options' => ['class' => 'form-control', 'name' => 'game_ids[]', 'multiple' => 'true'],
          ])->label('Tên game')?>
          
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
        </div>
        <?php ActiveForm::end()?>
        <div class="row">
          <div class="col-md-12">
            <?php Pjax::begin(); ?>
            <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
              <thead>
                <tr>
                  <th style="vertical-align: middle; text-align: center" rowspan="2"> STT </th>
                  <th style="vertical-align: middle; text-align: center" rowspan="2"> Tên game </th>
                  <?php if ($search->period == 'day') : ?>
                  <th style="vertical-align: middle; text-align: center" colspan="4">Thống kê theo ngày</th>
                  <?php else : ?>
                  <?php foreach ($models as $date => $records) :?>
                  <th style="vertical-align: middle; text-align: center" colspan="4"><?=$search->getLabelByPeriod($date);?></th>
                  <?php endforeach;?>
                  <?php endif;?>
                </tr>
                <tr>
                  <?php if ($search->period == 'day') : ?>
                  <th style="vertical-align: middle; text-align: center">Số lượng gói</th>
                  <th style="vertical-align: middle; text-align: center">Doanh thu (Nghìn đồng)</th>
                  <th style="vertical-align: middle; text-align: center">Chi phí (Nghìn đồng)</th>
                  <th style="vertical-align: middle; text-align: center">Lợi nhuận (Nghìn đồng)</th>
                  <?php else : ?>
                  <?php foreach ($models as $date => $records) :?>
                  <th style="vertical-align: middle; text-align: center">Số lượng gói</th>
                  <th style="vertical-align: middle; text-align: center">Doanh thu (Nghìn đồng)</th>
                  <th style="vertical-align: middle; text-align: center">Chi phí (Nghìn đồng)</th>
                  <th style="vertical-align: middle; text-align: center">Lợi nhuận (Nghìn đồng)</th>
                  <?php endforeach;?>
                  <?php endif;?>
                </tr>
              </thead>
              <tbody>
                  <?php if (!$models) :?>
                  <tr><td colspan="<?=($search->period == 'day') ? 6 : 2;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
                  <?php endif;?>
                  <?php foreach (array_values($gameReports) as $no => $game):?>
                  <tr>
                    <td style="vertical-align: middle; text-align: center"><?=++$no;?></td>
                    <td style="vertical-align: middle; text-align: left; padding-left: 8px"><?=$game['game_title'];?></td>
                    <?php if ($search->period == 'day') : ?>
                    <?php $reportData = $game['dates']; ?>
                    <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'quantity')), 1);?></td>
                    <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reportData, 'total_price')), 1) * $rate;?></td>
                    <td style="vertical-align: middle; text-align: center"></td>
                    <td style="vertical-align: middle; text-align: center"></td>
                    <?php else : ?>
                    <?php foreach ($dates as $date): ?>
                    <?php $reportData = ArrayHelper::getValue($game['dates'], $date, ['quantity' => 0, 'total_price' => 0]);?>
                    <td style="vertical-align: middle; text-align: center"><?=round($reportData['quantity'], 1);?></td>
                    <td style="vertical-align: middle; text-align: center"><?=round($reportData['total_price'], 1) * $rate;?></td>
                    <td style="vertical-align: middle; text-align: center"></td>
                    <td style="vertical-align: middle; text-align: center"></td>
                    <?php endforeach;?>
                    <?php endif;?>
                  </tr>
                  <?php endforeach;?>
              </tbody>
              <?php if ($models) :?>
              <tfoot>
                <tr>
                  <td></td>
                  <td><strong>Tổng:</strong></td>
                  <?php if ($search->period == 'day') : ?>
                  <td style="vertical-align: middle; text-align: center"><?=round($search->getCommand()->sum('quantity'), 1);?></td>
                  <td style="vertical-align: middle; text-align: center"><?=number_format($search->getCommand()->sum('total_price') * $rate);?></td>
                  <td></td>
                  <td></td>
                  <?php else : ?>
                  <?php foreach ($models as $reports): ?>
                  <td style="vertical-align: middle; text-align: center"><?=round(array_sum(array_column($reports, 'quantity')), 1);?></td>
                  <td style="vertical-align: middle; text-align: center"><?=number_format(array_sum(array_column($reports, 'total_price'))) * $rate;?></td>
                  <td></td>
                  <td></td>
                  <?php endforeach;?>
                  <?php endif;?>
                </tr>
              </tfoot>
              <?php endif;?>
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