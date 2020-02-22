<?php
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use backend\components\datetimepicker\DateTimePicker;
$models = $search->getReport();
$pages = $search->getPage();
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
      <span>Theo nhà cung cấp</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo nhà cung cấp</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê theo nhà cung cấp</span>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/cost-supplier']]);?>
        <div class="row margin-bottom-10">
            <?= $form->field($search, 'report_from', [
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
            ])->label('Ngày tạo từ');?>

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
            ])->label('Ngày tạo đến');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-bordered table-checkable">
            <thead>
              <tr>
                <th> Ngày thực hiện </th>
                <th> Mã đơn hàng </th>
                <th> Tên game </th>
                <th> Số gói </th>
                <th> Giá Kinggems (VNĐ) </th>
                <th> Giá nhà cung cấp (VNĐ)</th>
                <th> Lợi nhuận (VNĐ) </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <?php $numSuppliers = count($model['suppliers']);?>
                <?php $suppliers = $model['suppliers'];?>
                <?php $total_price = $model['total_price'];?>
                <?php foreach ($suppliers as $no => $supplier) : ?>
                <tr>
                  <?php if (!$no) : ?>
                  <td rowspan="<?=$numSuppliers;?>"><?=$model['confirmed_at'];?></td>
                  <td rowspan="<?=$numSuppliers;?>"><?=$model['id'];?></td>
                  <td rowspan="<?=$numSuppliers;?>"><?=sprintf("%s (#%s)", $model['game_title'], $model['game_id']);?></td>
                  <td rowspan="<?=$numSuppliers;?>"><?=$model['quantity'];?></td>
                  <td rowspan="<?=$numSuppliers;?>"><?=number_format($model['total_price']);?></td>
                  <?php endif;?>
                  <td class="numer"><?=sprintf("%s (Giá: %s, Hoàn thành: %s/%s)", number_format($supplier->total_price), number_format($supplier->price), $supplier->doing, $supplier->quantity);?></td>
                  <td class="numer"><?=number_format($model['total_price'] - $supplier['total_price']);?></td>
                </tr>
                <?php endforeach;?>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
