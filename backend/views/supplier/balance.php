<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use common\components\helpers\StringHelper;
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
      <span>Thống kê dòng tiền</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Số dư tài khoản nhà cung cấp</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Số dư tài khoản nhà cung cấp</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Thống kê số dư tài khoản nhà cung cấp</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['supplier/balance']]);?>
            <?php $user = $search->getCustomer();?>
            <?=$form->field($search, 'supplier_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->supplier_id) ? sprintf("%s - %s", $user->username, $user->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'supplier_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhà cung cấp',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhà cung cấp')?>
            <?=$form->field($search, 'report_from', [    
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'report_from', 'id' => 'report_from']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Ngày thống kê từ');?>

            <?=$form->field($search, 'report_to', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'report_to', 'id' => 'report_to']
            ])->widget(DateTimePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd hh:59',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d H:i'),
                  'minView' => '1'
                ],
            ])->label('Ngày thống kê đến');?>
            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> ID </th>
              <th> Nhà cung cấp </th>
              <th> Số tiền nạp vào</th>
              <th> Số tiền rút ra </th>
              <th> Số dư đầu kỳ </th>
              <th> Số dư cuối kỳ </th>
              <th> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $id => $model) :?>
              <tr>
                <td>#<?=$id?></td>
                <td><?=$model['name']?></td>
                <td><?=StringHelper::numberFormat($model['period_income'], 2);?></td>
                <td><?=StringHelper::numberFormat(abs($model['period_outcome']), 2);?></td>
                <td><?=StringHelper::numberFormat($model['beginning_total'], 2);?></td>
                <td><?=StringHelper::numberFormat($model['ending_total'], 2);?></td>
                <td>
                  <a class="btn btn-xs green tooltips" href="<?=Url::to(['supplier/balance-detail', 'id' => $id]);?>" data-container="body" data-original-title="Xem chi tiết" target="_blank" data-pjax="0"><i class="fa fa-eye"></i></a>
                  <?php if (Yii::$app->user->can('admin')) : ?>
                  <a class="btn btn-xs purple tooltips" href="<?=Url::to(['supplier/topup', 'id' => $id]);?>" data-container="body" data-original-title="Topup wallet" target="_blank" data-pjax="0"><i class="fa fa-plus"></i></a>
                  <a class="btn btn-xs grey tooltips" href="<?=Url::to(['supplier/withdraw', 'id' => $id]);?>" data-container="body" data-original-title="Withdraw wallet" target="_blank" data-pjax="0"><i class="fa fa-minus"></i></a>
                  <?php endif;?>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <tfoot style="background-color: #999;">
            <tr>
              <th colspan="2"></th>
              <th> <?=StringHelper::numberFormat($search->getTotalIncome(), 2);?></th>
              <th> <?=StringHelper::numberFormat(abs($search->getTotalOutcome()), 2);?> </th>
              <th> </th>
              <th> <?=StringHelper::numberFormat($search->getTotalEndding(), 2);?> </th>
              <th> </th>
            </tr>
          </tfoot>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>