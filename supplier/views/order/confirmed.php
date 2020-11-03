<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use supplier\components\datetimepicker\DateTimePicker;
use supplier\models\User;
use common\components\helpers\FormatConverter;
use common\components\helpers\StringHelper;
use supplier\models\Order;


$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\supplier\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\supplier\assets\AppAsset']);

?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng</span>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET']);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'order_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'order_id']
            ])->textInput()->label('Mã đơn hàng');?>

            <?php $game = $search->getGame();?>   
            <?=$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($game) ? $game->title : '',
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
          
            <?= $form->field($search, 'start_date', [
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
            ])->label('Ngày xác nhận từ');?>

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
            ])->label('Ngày xác nhận đến');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable" id="order-table">
            <thead>
              <tr>
                <th col-tag="order_id"> Mã đơn hàng </th>
                <th col-tag="game_title"> Shop game </th>
                <th col-tag="unit"> Số lượng nạp </th>
                <th col-tag="quantity"> Số gói </th>
                <th col-tag="approved_at"> Thời điểm nhận đơn </th>
                <th col-tag="completed_time"> Tổng TG hoàn thành </th>
                <th col-tag="approved_time"> TG nhận đơn </th>
                <th col-tag="login_time"> TG login </th>
                <th col-tag="processing_time"> TG nạp </th>
                <th col-tag="confirmed_time"> TG xác nhận </th>
                <th col-tag="status"> Trạng thái </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="11"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <tr>
                  <td col-tag="order_id" class="center" style="max-width: none"><a href='<?=Url::to(['order/edit', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->order_id;?></a></td>
                  <td col-tag="game_title" class="center"><?=$model->getGameTitle();?></td>
                  <td col-tag="unit" class="center"><?=$model->unit;?></td>
                  <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($model->doing, 2);?></td>
                  <td col-tag="approved_at" class="center"><?=$model->approved_at;?></td>
                  <td col-tag="completed_time" class="center"><?=number_format($model->completed_time);?></td>
                  <td col-tag="approved_time" class="center"><?=number_format($model->approved_time);?></td>
                  <td col-tag="login_time" class="center"><?=number_format($model->login_time);?></td>
                  <td col-tag="processing_time" class="center"><?=number_format($model->processing_time);?></td>
                  <td col-tag="confirmed_time" class="center"><?=number_format($model->confirmed_time);?></td>
                  <td col-tag="status" class="center">
                    <span class="label label-default">Completed</span>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <?php $summary = $search->getSummary();?>
            <tfoot style="background-color: #999;">
              <td col-tag="order_id"><?=number_format($summary['count']);?></td>
              <td col-tag="game_title"></td>
              <td col-tag="unit" class="center"></td>
              <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($summary['quantity'], 2);?></td>
              <td col-tag="approved_at"></td>
              <td col-tag="completed_time" class="center"><?=number_format($summary['completed_time']);?></td>
              <td col-tag="approved_time" class="center"><?=number_format($summary['approved_time']);?></td>
              <td col-tag="login_time" class="center"><?=number_format($summary['login_time']);?></td>
              <td col-tag="processing_time" class="center"><?=number_format($summary['processing_time']);?></td>
              <td col-tag="confirmed_time"><?=number_format($summary['confirmed_time']);?></td>
              <td col-tag="status"></td>
            </tfoot>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
