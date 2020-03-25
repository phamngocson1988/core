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

$settings = Yii::$app->settings;
$rate = (float)$settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 22000);
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
      <span>Theo đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê theo đơn hàng</span>
        </div>
        <div class="actions">
          <a role="button" class="btn btn-success" href="<?=Url::to(['report/cost-order-statistics', 'start_date' => $search->confirmed_from, 'end_date' => $search->confirmed_to, 'period' => 'day']);?>"><i class="fa fa-bar-chart"></i> Biểu đồ</a>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report-profit/order']]);?>
        <div class="row">
          <?=$form->field($search, 'id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'id']
          ])->textInput()->label('Mã đơn hàng');?>

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

          <?=$form->field($search, 'payment_method', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'payment_method', 'id' => 'payment_method']
          ])->dropDownList($search->fetchPaymentMethods())->label('Cổng thanh toán');?>

          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th> Mã đơn hàng </th>
                <th> Nhà cung cấp </th>
                <th> Khách hàng </th>
                <th> Ngày xác nhận </th>
                <th> Tên game </th>
                <th> Số gói </th>
                <th> Gía bán (VNĐ/gói) </th>
                <th> Tiền bán (VNĐ) </th>
                <th> Giá mua (VNĐ/ gói) </th>
                <th> Chi phí (VNĐ/ gói) </th>
                <th> Lợi nhuận (VNĐ)</th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="11"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <?php
                $order = $model->order;
                // USD to VND
                $kinggemsPrice = ($order->quantity) ? $order->total_price * $rate / $order->quantity : 0; 
                $totalKinggemsPrice = $kinggemsPrice * $model->doing; 
                // VND
                $supplierPrice = $model->price;
                $totalSupplierPrice = $model->price * $model->doing;
                ?>
                <tr>
                  <td><a href='<?=Url::to(['order/view', 'id' => $model->order_id]);?>'><?=$model->order_id;?></a></td>
                  <td><?=$model->user->name;?></td>
                  <td><?=$order->customer_name;?></td>
                  <td><?=$order->confirmed_at;?></td>
                  <td><?=$model->getGameTitle();?></td>
                  <td><?=number_format($model->quantity);?> / <?=number_format($order->quantity);?></td>
                  <td><?=number_format($kinggemsPrice);?></td>
                  <td><?=number_format($totalKinggemsPrice);?></td>
                  <td><?=number_format($supplierPrice);?></td>
                  <td><?=number_format($totalSupplierPrice);?></td>
                  <td><?=number_format($totalKinggemsPrice - $totalSupplierPrice);?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>