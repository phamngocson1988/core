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
?>
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
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report-profit/game']]);?>
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
          
          <?=$form->field($search, 'confirmed_from', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'confirmed_from', 'id' => 'confirmed_from']
          ])->widget(DateTimePicker::className(), [
            'clientOptions' => [
              'autoclose' => true,
              'format' => 'yyyy-mm-dd hh:00',
              'minuteStep' => 1,
              'endDate' => date('Y-m-d H:i'),
              'minView' => '1'
            ],
          ])->label('Ngày xác nhận từ');?>

          <?=$form->field($search, 'confirmed_to', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'confirmed_to', 'id' => 'confirmed_to']
          ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:59',
                'todayBtn' => true,
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
          ])->label('Ngày xác nhận đến');?>

          <!-- <div class='form-group col-md-4 col-lg-3'>
            <label class='control-label'>Thống kê theo:</label>
            <div class="clearfix">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn red <?=($search->period == 'day') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="day" <?=($search->period == 'day') ? 'checked="checked"' : '';?> > Ngày </label>
                <label class="btn red <?=($search->period == 'week') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="week" <?=($search->period == 'week') ? 'checked="checked"' : '';?> > Tuần </label>
                <label class="btn red <?=($search->period == 'month') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="month" <?=($search->period == 'month') ? 'checked="checked"' : '';?> > Tháng </label>
                <label class="btn red <?=($search->period == 'quarter') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="quarter" <?=($search->period == 'quarter') ? 'checked="checked"' : '';?> > Quý </label>
              </div>
            </div>
          </div>	 -->

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
              <thead>
                <tr>
                  <th> Mã game </th>
                  <th> Tên game </th>
                  <th> Số lượng gói </th>
                  <th> Doanh thu (nghìn VNĐ) </th>
                  <th> Chi phí (nghìn VNĐ) </th>
                  <th> Lợi nhuận (nghìn VNĐ) </th>
                </tr>
              </thead>
              <tbody>
                  <?php if (!$models) :?>
                  <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
                  <?php endif;?>
                  <?php foreach (array_values($models) as $model):?>
                  <tr>
                    <td><?=$model['game_id'];?></td>
                    <td><?=$model['game']['title'];?></td>
                    <td><?=number_format($model['sum_doing'], 1);?></td>
                    <td><?=number_format($model['sum_revenue'] / 1000, 1);?></td>
                    <td><?=number_format($model['sum_fee'] / 1000, 1);?></td>
                    <td><?=number_format($model['sum_profit'] / 1000, 1);?></td>
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