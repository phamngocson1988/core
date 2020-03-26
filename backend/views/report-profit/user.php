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
      <span>Theo nhân viên</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chi phí lợi nhuận theo nhân viên</h1>
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
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report-profit/user']]);?>
        <div class="row">
          <?=$form->field($search, 'saler_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'saler_id', 'id' => 'saler_id']
          ])->dropDownList($search->fetchUsers(), ['prompt' => 'Chọn nhân viên'])->label('Chọn nhân viên');?>

          
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

          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
      </div>
    </div>
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <span class="caption-subject bold uppercase"> Theo game</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th> STT </th>
                  <th> Game </th>
                  <th> Số gói </th>
                  <th> Tỉ lệ </th>
                </tr>
              </thead>
              <tbody>
                  <?php if (!$games) :?>
                  <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
                  <?php endif;?>
                  <?php foreach ($games as $no => $game):?>
                  <tr>
                    <td><?=($no + 1);?></td>
                    <td><?=$game['game_title'];?></td>
                    <td><?=number_format($game['quantity'], 1);?></td>
                    <td><?=number_format($game['quantity'] * 100 / $sumQuantityGame, 1);?>%</td>
                  </tr>
                  <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2">Tổng: <?=number_format($totalQuantityGame);?></td>
                  <td><?=number_format($sumQuantityGame, 1);?></td>
                  <td>100%</td>
                </tr>
              </tfoot>
            </table>
          </div>
          
        </div>
      </div>
    </div>
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <span class="caption-subject bold uppercase"> Theo khách hàng</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th> STT </th>
                  <th> Khách hàng </th>
                  <th> Thành tiền (VNĐ) </th>
                  <th> Số gói </th>
                  <th> Tỉ lệ </th>
                </tr>
              </thead>
              <tbody>
                  <?php if (!$customers) :?>
                  <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
                  <?php endif;?>
                  <?php foreach ($customers as $no => $customer):?>
                  <tr>
                    <td><?=($no + 1);?></td>
                    <td><?=$customer['customer_name'];?></td>
                    <td><?=number_format($customer['total_price']);?></td>
                    <td><?=number_format($customer['quantity'], 1);?></td>
                    <td><?=number_format($customer['quantity'] * 100 / $sumQuantityCustomer, 1);?>%</td>
                  </tr>
                  <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2">Tổng: <?=number_format($totalQuantityCustomer);?></td>
                  <td><?=number_format($sumPriceCustomer);?></td>
                  <td><?=number_format($sumQuantityCustomer, 1);?></td>
                  <td>100%</td>
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