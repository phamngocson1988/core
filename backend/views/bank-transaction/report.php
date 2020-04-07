<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\components\helpers\CommonHelper;
use backend\components\datetimepicker\DateTimePicker;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['bank/index']);?>">Danh sách ngân hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê giao dịch</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thống kê giao dịch</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
      <div class="visual">
        <i class="fa fa-comments"></i>
      </div>
      <div class="details">
        <div class="number">
          <span data-counter="counterup" data-value="<?=number_format($search->getTotalTransaction());?>">0</span>
        </div>
        <div class="desc"> Tổng giao dịch trong kỳ </div>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 red" href="#">
      <div class="visual">
        <i class="fa fa-bar-chart-o"></i>
      </div>
      <div class="details">
        <div class="number">
          <span data-counter="counterup" data-value="<?=number_format($search->getTotalAmountByFromDate());?>">0</span> 
        </div>
        <div class="desc"> Tổng tiền đầu kỳ </div>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 green" href="#">
      <div class="visual">
        <i class="fa fa-shopping-cart"></i>
      </div>
      <div class="details">
        <div class="number">
          <span data-counter="counterup" data-value="<?=number_format($search->getTotalAmount());?>">0</span>
        </div>
        <div class="desc"> Tổng tiền trong kỳ </div>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
      <div class="visual">
        <i class="fa fa-globe"></i>
      </div>
      <div class="details">
        <div class="number">
          <span data-counter="counterup" data-value="<?=number_format($search->getTotalAmountByToDate());?>"></span>
        </div>
        <div class="desc"> Tổng tiền cuối kỳ </div>
      </div>
    </a>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
        <div class="actions">
          <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>
        </div>
      </div>

      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['bank-transaction/report-by-user', 'currency' => $currency]]);?>
            <?= $form->field($search, 'from_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'from_date', 'id' => 'from_date']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'endDate' => date('Y-m-d'),
                'minView' => '2'
              ],
            ])->label('Ngày hoàn thành từ');?>
            <?=$form->field($search, 'to_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'to_date', 'id' => 'to_date']
            ])->widget(DateTimePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d'),
                  'minView' => '2'
                ],
            ])->label('Ngày hoàn thành đên');?>
            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search');?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Ngày hoàn thành </th>
              <th> Ngân hàng </th>
              <th> Tài khoản </th>
              <th> Loại giao dịch </th>
              <th> Số tiền </th>
              <th> Ghi chú </th>
              <th> Hoàn thành bởi </th>
              <th> Trạng thái </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="9"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td><?=$model->created_at;?></td>
                <td><?=$model->bank->name;?></td>
                <td><?=sprintf("%s %s", $model->bankAccount->account_name, $model->bankAccount->account_number);?></td>
                <td>
                  <?php if ($model->isTypeIn()) : ?>
                    <span class="label label-info"> Nạp tiền </span>
                  <?php else :?>
                    <span class="label label-warning"> Chuyển tiền </span>
                  <?php endif;?>
                </td>
                <td><?=sprintf("%s (%s)", number_format(abs($model->amount)), $model->currency);?></td>
                <td><?=nl2br($model->description);?></td>
                <td><?=$model->executor->name;?></td>
                <td>
                  <?php if ($model->isPending()) : ?>
                    <span class="label label-default"> Giao dịch tạm </span>
                  <?php else :?>
                    <span class="label label-primary"> Đã hoàn thành </span>
                  <?php endif;?>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>