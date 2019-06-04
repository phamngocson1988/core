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
      <span>Doanh số theo đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Doanh số theo đơn hàng</h1>
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
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/sale-order-statistics']]);?>
        <div class="row">
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

          <div class='form-group col-md-4 col-lg-3'>
            <label class='control-label'>Thống kê theo:</label>
            <div class="clearfix">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn red <?=($search->period == 'day') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="day"> Ngày </label>
                <label class="btn red <?=($search->period == 'week') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="week"> Tuần </label>
                <label class="btn red <?=($search->period == 'month') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="month"> Tháng </label>
                <label class="btn red <?=($search->period == 'quarter') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="quarter"> Quý </label>
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
          
          <div class="col-md-6">
          <?php Pjax::begin(); ?>
          <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
            <thead>
                <tr>
                <th style="width: 10%;"> STT </th>
                <th style="width: 30%;"> Ngày </th>
                <th style="width: 30%;"> Số gói </th>
                <th style="width: 30%;"> Số Kcoin </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <tr>
                    <td style="vertical-align: middle;"><?=$no + $pages->offset + 1;?></td>
                    <td style="vertical-align: middle;">
                      <?php switch ($search->period) {
                        case 'quarter':
                          echo sprintf("Quý %s (%s)", str_pad($model['quarter'], 2, "0", STR_PAD_LEFT), $model['year']);
                        break;
                        case 'month':
                          echo sprintf("Tháng %s (%s)", str_pad($model['month'], 2, "0", STR_PAD_LEFT), $model['year']);
                          break;
                        case 'week':
                          $week = str_pad($model['week'] + 1, 2, "0", STR_PAD_LEFT);
                          $dateOfWeek = sprintf("%sW%s", $model['year'], $week);
                          $startWeek = date('Y-m-d',strtotime($dateOfWeek));
                          $endWeek = date('Y-m-d',strtotime($startWeek . " + 7 days"));
                          echo sprintf("Tuần %s (%s - %s)", $week, $startWeek, $endWeek);
                          break;
                        default:
                          echo sprintf("%s-%s-%s", $model['year'], str_pad($model['month'], 2, "0", STR_PAD_LEFT)  , str_pad($model['day'], 2, "0", STR_PAD_LEFT));
                          break;
                      };?>
                    </td>
                    <td style="vertical-align: middle;"><?=round($model['game_pack'], 1);?></td>
                    <td style="vertical-align: middle;"><?=number_format($model['total_price']);?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                <td></td>
                <td><strong>Tổng:</strong></td>
                <td><?=round($search->getCommand()->sum('game_pack'), 1);?></td>
                <td><?=number_format($search->getCommand()->sum('total_price'));?></td>
                </tr>
            </tfoot>
            </table>
            <?=LinkPager::widget(['pagination' => $pages])?>
            <?php Pjax::end(); ?>
          </div>    
          <div class="col-md-6">
          <?=$search->showChar();?>
          </div>
        </div>
        
        
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>