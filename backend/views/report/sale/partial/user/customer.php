<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\Order;
use yii\helpers\ArrayHelper;
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
      <span>Thống kê bán hàng</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Doanh số theo nhân viên</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Danh sách khách hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Danh sách khách hàng của saler <strong><?=$saler->name;?></strong></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Danh sách khách hàng <?=sprintf("(%s - %s)", $search->start_date, $search->end_date);?></span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th> Mã khách hàng </th>
                  <th> Tên khách hàng </th>
                  <th> Số đơn hàng </th>
                  <th> Số gói </th>
                  <th> Số Kcoin </th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="5"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model):?>
                <tr>
                  <td> <?=$model['customer_id'];?> </td>
                  <td> <?=$model['customer_name'];?> </td>
                  <td> <a href="<?=Url::to(['report/sale-user-order', 'saler_id' => $model['saler_id'], 'customer_id' => $model['customer_id'], 'start_date' => $search->start_date, 'end_date' => $search->end_date]);?>"><?=$model['total_order'];?> </td>
                  <td> <?=$model['quantity'];?> </td>
                  <td> <?=$model['total_price'];?> </td>
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
