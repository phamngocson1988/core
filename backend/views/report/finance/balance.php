<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use common\components\helpers\StringHelper;
use common\models\Order;

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
      <span>Số dư tài khoản khách hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Số dư tài khoản khách hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Thống kê số dư tài khoản khách hàng</span>
        </div>
        <div class="actions">
          <!-- <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a> -->
          <!-- <a role="button" class="btn btn-success" href="<?=Url::to(['report/finance-balance-statistics']);?>"><i class="fa fa-bar-chart"></i> Biểu đồ</a> -->
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/finance-balance']]);?>
            <?php $user = $search->getUser();?>
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
        
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable hidden" id="order-table">
            <thead>
              <tr>
                <th col-tag="id"> <?=Yii::t('app', 'no');?> </th>
                <th col-tag="customer"> Khách hàng </th>
                <th col-tag="topup"> Số tiền nạp </th>
                <th col-tag="withdraw"> Số tiền mua hàng</th>
                <th col-tag="balance_start"> Số dư ban đầu</th>
                <th col-tag="balance_end"> Số dư hiện tại </th>
                <th col-tag="actions"> Tác vụ </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$report) :?>
                <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($report as $userId => $r) :?>
                <tr>
                  <td col-tag="id">#<?=$userId?></td>
                  <td col-tag="customer" class="center"><?=$r['name'];?></td>
                  <td col-tag="topup" class="center"><?=StringHelper::numberFormat($r['topup'], 2);?></td>
                  <td col-tag="withdraw" class="center"><?=StringHelper::numberFormat($r['withdraw'], 2);?></td>
                  <td col-tag="balance_start" class="center">$<?=StringHelper::numberFormat($r['balance_start'], 2);?></td>
                  <td col-tag="balance_end" class="center">$<?=StringHelper::numberFormat($r['balance_end'], 2);?></td>
                  <td col-tag="actions" class="center">
                    <a class="btn btn-xs green tooltips" href="<?=Url::to(['report/finance-balance-detail', 'user_id' => $userId, 'start_date' => $search->start_date, 'end_date' => $search->end_date]);?>" data-container="body" data-original-title="Xem chi tiết" target="_blank" data-pjax="0"><i class="fa fa-eye"></i></a>
                    <?php if (Yii::$app->user->can('admin')) : ?>
                    <a class="btn btn-xs purple tooltips" href="<?=Url::to(['wallet/topup', 'id' => $userId]);?>" data-container="body" data-original-title="Topup wallet" target="_blank" data-pjax="0"><i class="fa fa-plus"></i></a>
                    <a class="btn btn-xs grey tooltips" href="<?=Url::to(['wallet/withdraw', 'id' => $userId]);?>" data-container="body" data-original-title="Withdraw wallet" target="_blank" data-pjax="0"><i class="fa fa-minus"></i></a>
                    <?php endif;?>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
              <tr>
                <th col-tag="id"></th>
                <th col-tag="customer"></th>
                <th col-tag="topup"> <?=StringHelper::numberFormat($finalInCome, 2);?> </th>
                <th col-tag="withdraw"> <?=StringHelper::numberFormat($finalOutCome, 2);?></th>
                <th col-tag="balance_start"></th>
                <th col-tag="balance_end"><?=StringHelper::numberFormat($finalBalance, 2);?></th>
                <th col-tag="actions"></th>
              </tr>
            </tfoot>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$hiddenColumns = [];
if (Yii::$app->user->isRole('saler')) array_push($hiddenColumns, 'topup', 'withdraw', 'balance_start');
$hiddenColumnString = implode(',', $hiddenColumns);
$script = <<< JS
var hiddenColumns = '$hiddenColumnString';
initTable('#order-table', '#no-data', hiddenColumns);
JS;
$this->registerJs($script);
?>