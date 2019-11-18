<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\Order;
use yii\helpers\ArrayHelper;
$command = $search->getCommand();
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
      <span>Danh sách đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Danh sách đơn hàng của saler <strong><?=$saler->name;?></strong></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Khách hàng <strong><?=$customer->name;?></strong> <?=sprintf("(%s - %s)", $search->start_date, $search->end_date);?></span>
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
                  <th>ID</th>
                  <th>Khách hàng</th>
                  <th>Số gói</th>
                  <th>Số tiền</th>
                  <th>Ngày tạo</th>
                  <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($models as $model) : ?>
                <tr>
                  <td><?=$model->id;?></td>
                  <td><?=$model->customer_name;?></td>
                  <td><?=$model->quantity;?></td>
                  <td><?=$model->total_price;?></td>
                  <td><?=$model->created_at;?></td>
                  <td><?=$model->status;?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2"></td>
                  <td><?=$command->sum('quantity');?></td>
                  <td><?=$command->sum('total_price');?></td>
                  <td colspan="2"></td>
                </tr>
              </tfoot>
            </table>

          </div>
        </div>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
