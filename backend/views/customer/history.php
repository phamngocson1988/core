<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\models\TransactionHistory;
use dosamigos\datepicker\DateRangePicker;
$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile('vendor/assets/apps/css/todo.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
?>
<style>
.hide-text {
    white-space: nowrap;
    width: 100%;
    max-width: 500px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Lịch sử giao dịch</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Lịch sử giao dịch</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Lịch sử giao dịch</span>
        </div>
        <div class="actions">
          <a href="<?=Url::to(['customer/index']);?>" class="btn default"><i class="fa fa-angle-left"></i> Trở về</a>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['customer/history', 'id' => $customer_id]]);?>     

            <?=$form->field($search, 'q', [
              'inputOptions' => ['name' => 'q', 'class' => 'form-control'],
              'options' => ['class' => 'form-group col-md-2']
            ])->textInput()->label('Keyword');?>

            <?=$form->field($search, 'transaction_type', [
              'options' => ['class' => 'form-group col-md-2'],
              'inputOptions' => ['name' => 'transaction_type', 'class' => 'form-control'],
            ])->dropDownList(TransactionHistory::getTypeList(), ['prompt' => Yii::t('app', 'choose')])->label('Loại giao dịch');?>

            <?=$form->field($search, 'start_date', [
              'options' => ['class' => 'form-group col-md-2'],
              'inputOptions' => ['name' => 'start_date', 'class' => 'form-control']
            ])->widget(DateRangePicker::className(), [
              'attributeTo' => 'end_date', 
              'optionsTo' => ['name' => 'end_date', 'class' => 'form-control'],
              'labelTo' => '-',
              'form' => $form,
              'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                  'keepEmptyValues' => true,
                  'todayHighlight' => true
              ]
            ])->label('Từ ngày')?>

            <div class="form-group col-md-2">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> STT </th>
              <th style="width: 30%;"> Khách hàng </th>
              <th style="width: 10%;"> Loại giao dịch </th>
              <th style="width: 15%;"> Số tiền </th>
              <th style="width: 30%;"> Mô tả </th>
              <th style="width: 10%;"> Thời gian </th>
            </tr>
          </thead>
          <tbody>
            <?php if ($models) :?>
            <?php foreach ($models as $key => $model): ?>
            <tr>
              <td><?=$key + $pages->offset + 1;?></td>
              <td><?=$model->customer->company;?> - <?=$model->customer->name;?></td>
              <td><?=$model->getTypeLabel();?></td>
              <td><?=$model->amount;?> VNĐ</td>
              <td><?=$model->description;?></td>
              <td><?=$model->created_at;?></td>
            </tr>
            <?php endforeach;?>
            <?php else :?>
            <tr>
              <td colspan="6"><?=Yii::t('app', 'no_data_found');?></td>
            </tr>
            <?php endif;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>