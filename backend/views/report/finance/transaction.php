<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\Order;
use common\models\User;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
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
      <span>Thống kê & báo cáo</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê dòng tiền</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Giao dịch nạp tiền</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Giao dịch nạp tiền</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê theo giao dịch nạp tiền</span>
        </div>
        <div class="actions">
          <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a>
          <a role="button" class="btn btn-success" href="<?=Url::to(['report/finance-transaction-statistics', 'start_date' => $search->start_date, 'end_date' => $search->end_date, 'period' => 'day']);?>"><i class="fa fa-bar-chart"></i> Biểu đồ</a>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/finance-transaction']]);?>
            <?php $user = $search->user;?>
            <?=$form->field($search, 'user_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $user->username, $user->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'user_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn khách hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Khách hàng')?>

            <?=$form->field($search, 'promotion_code', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'promotion_code']
            ])->textInput()->label('Mã khuyến mãi');?>

            <?=$form->field($search, 'auth_key', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'auth_key']
            ])->textInput()->label('Mã giao dịch');?>

            <?=$form->field($search, 'is_reseller', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_reseller']
            ])->dropDownList(User::getResellerStatus(),  ['prompt' => 'Tất cả'])->label('Reseller/Khách hàng');?>

            <?=$form->field($search, 'start_date', [    
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'start_date', 'id' => 'start_date']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Ngày tạo từ');?>

            <?=$form->field($search, 'end_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'end_date', 'id' => 'end_date']
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
          <?php ActiveForm::end()?>
        </div>
        
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 15%;"> Thời gian </th>
              <th style="width: 15%;"> Khách hàng </th>
              <th style="width: 10%;"> Loại khách hàng </th>
              <th style="width: 10%;"> Mã giao dịch </th>
              <th style="width: 10%;"> Khuyến mãi Kcoin</th>
              <th style="width: 10%;"> Số lượng Kcoin</th>
              <th style="width: 10%;"> Giảm giá </th>
              <th style="width: 10%;"> Số tiền </th>
              <th style="width: 5%;"> Trạng thái </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="10"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td>#<?=($pages->offset + $no + 1)?></td>
                <td style="vertical-align: middle;"><?=$model->payment_at;?></td>
                <td style="vertical-align: middle;"><?=$model->user->name;?></td>
                <td style="vertical-align: middle;"><?=($model->user->isReseller()) ? 'Reseller' : 'Customer';?></td>
                <td style="vertical-align: middle;"><?=$model->getId();?></td>
                <td style="vertical-align: middle;"><?=number_format($model->promotion_coin);?></td>
                <td style="vertical-align: middle;"><?=number_format($model->total_coin);?></td>
                <td style="vertical-align: middle;">$<?=number_format($model->discount_price);?></td>
                <td style="vertical-align: middle;">$<?=number_format($model->total_price);?></td>
                <td style="vertical-align: middle;"><?=$model->status;?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td></td>
              <td style="vertical-align: middle;"></td>
              <td style="vertical-align: middle;"></td>
              <td style="vertical-align: middle;"></td>
              <td style="vertical-align: middle;">Tổng: <?=number_format($search->getCommand()->count());?></td>
              <td style="vertical-align: middle;">Tổng: <?=number_format($search->getCommand()->sum('promotion_coin'));?></td>
              <td style="vertical-align: middle;">Tổng: <?=number_format($search->getCommand()->sum('total_coin'));?></td>
              <td style="vertical-align: middle;"></td>
              <td style="vertical-align: middle;">Tổng: $<?=number_format($search->getCommand()->sum('total_price'));?></td>
              <td style="vertical-align: middle;"></td>
            </tr>
          </tfoot>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>