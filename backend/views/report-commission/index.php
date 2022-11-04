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
use backend\models\OrderCommission;
use common\models\User;
use common\components\helpers\FormatConverter;
use dosamigos\chartjs\ChartJs;
use common\components\helpers\StringHelper;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

$commissions = $search->getData();
$commissionDetailByUsers = ArrayHelper::index($commissions, null, 'user_id');
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
      <span>Theo hoa hồng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo hoa hồng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê theo hoa hồng và sellout</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report-commission/index']]);?>
        <div class="row">
        <?=$form->field($search, 'user_ids', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'user_ids[]']
            ])->dropDownList($search->fetchUsers())->label('Nhân viên');?>
          <?=$form->field($search, 'start_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'start_date', 'id' => 'start_date']
          ])->widget(DateTimePicker::className(), [
            'clientOptions' => [
              'autoclose' => true,
              'format' => 'yyyy-mm-dd',
              'minuteStep' => 1,
              'endDate' => date('Y-m-d'),
              'minView' => '2'
            ],
          ])->label('Ngày tạo từ');?>

          <?=$form->field($search, 'end_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'end_date', 'id' => 'end_date']
          ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true,
                'minuteStep' => 1,
                'endDate' => date('Y-m-d'),
                'minView' => '2'
              ],
          ])->label('Ngày tạo đến');?>
          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th> Nhân viên </th>
                  <th> Sell out </th>
                  <th> Order </th>
                  <th> Tổng </th>
                </tr>
              </thead>
              <tbody>
                <?php $dataByUser = $search->getCommissionByUser();?>
                <?php if (!count($dataByUser)) :?>
                <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($dataByUser as $userId => $commission) :?>
                <tr>
                  <td class="center"><?=$commission['name'];?></td>
                  <td class="center"><a href="<?=Url::to(['report-commission/detail', 'user_id' => $commission['user_id'], 'start_date' => $search->start_date, 'end_date' => $search->end_date, 'type' => OrderCommission::COMMSSION_TYPE_SELLOUT]);?>" target="_blank"><?=StringHelper::numberFormat($commission[OrderCommission::COMMSSION_TYPE_SELLOUT], 0);?></a></td>
                  <td class="center"><a href="<?=Url::to(['report-commission/detail', 'user_id' => $commission['user_id'], 'start_date' => $search->start_date, 'end_date' => $search->end_date, 'type' => OrderCommission::COMMSSION_TYPE_ORDER]);?>" target="_blank"><?=StringHelper::numberFormat($commission[OrderCommission::COMMSSION_TYPE_ORDER], 0);?></a></td>
                  <td class="center"><?=StringHelper::numberFormat($commission[OrderCommission::COMMSSION_TYPE_SELLOUT] + $commission[OrderCommission::COMMSSION_TYPE_ORDER], 0);?></td>
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