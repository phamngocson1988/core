<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datepicker\DatePicker;
use backend\models\SupplierWallet;
use backend\models\SupplierWithdrawRequest;

$models = $search->getReport();
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
      <span>Báo cáo nhanh</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Báo cáo nhanh</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Báo cáo nhanh nhà cung cấp</span>
        </div>
        <div class="actions">
          <!-- <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a> -->
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['supplier/fast-report']]);?>

            <?=$form->field($search, 'report_from', [    
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'report_from', 'id' => 'report_from']
            ])->widget(DatePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'endDate' => date('Y-m-d'),
              ],
            ])->label('Ngày thống kê từ');?>

            <?=$form->field($search, 'report_to', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'report_to', 'id' => 'report_to']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d'),
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
              <th rowspan="2"> Ngày thống kê </th>
              <th colspan="2"> Doanh thu </th>
              <th rowspan="2"> Công nợ đầu ngày</th>
              <th rowspan="2"> Công nợ trong ngày </th>
              <th rowspan="2"> Yêu cầu rút tiền </th>
              <th rowspan="2"> Công nợ cuối ngày </th>
            </tr>
            <tr>
              <th> Số gói </th>
              <th> VNĐ </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="5"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $date => $model) :?>
              <tr>
                <td><?=$date;?></td>
                <td><a href="<?=Url::to(['report-profit/order', 'confirmed_from' => sprintf("%s 00:00", $date), 'confirmed_to' => sprintf("%s 23:59", $date)]);?>" target="_blank"><?=number_format($model['quantity'], 1);?></a></td>
                <td><a href="<?=Url::to(['report-profit/order', 'confirmed_from' => sprintf("%s 00:00", $date), 'confirmed_to' => sprintf("%s 23:59", $date)]);?>" target="_blank"><?=number_format($model['revenue']);?></a></td>
                <td><?=number_format($model['first_income']);?></td>
                <td><a href="<?=Url::to(['report-profit/order', 'confirmed_from' => sprintf("%s 00:00", $date), 'confirmed_to' => sprintf("%s 23:59", $date)]);?>" target="_blank"><?=number_format($model['income']);?></a></td>
                <td><a href="<?=Url::to(['supplier/withdraw-request', 'status' => SupplierWithdrawRequest::STATUS_DONE, 'done_from' => $date, 'done_to' => $date]);?>" target="_blank"><?=number_format(abs($model['outcome']));?></a></td>
                <td><?=number_format($model['last_income']);?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <?php if ($models) :?>
          <tfoot>
            <tr>
              <td>Tổng:</td>
              <td><?=number_format(array_sum(array_column($models, 'quantity')), 1);?></td>
              <td><?=number_format(array_sum(array_column($models, 'revenue')));?></td>
              <td>-</td>
              <td><?=number_format(array_sum(array_column($models, 'income')));?></td>
              <td><?=number_format(abs(array_sum(array_column($models, 'outcome'))));?></td>
              <td>-</td>
            </tr>
          </tfoot>
          <?php endif;?>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>