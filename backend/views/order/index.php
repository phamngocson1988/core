<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datepicker\DateRangePicker;
use common\models\Order;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);



$this->registerCssFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);


$this->registerJsFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-date-time-pickers.min.js', ['depends' => '\backend\assets\AppAsset']);
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
      <span><?=Yii::t('app', 'manage_tasks')?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_tasks')?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_tasks')?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <?php if (Yii::$app->user->can('admin')):?>
            <a class="btn purple" href="<?=Url::to(['order/index', 'handler_id' => '-1', 'status' => Order::STATUS_PENDING])?>">Chưa có người quản lý</a>
            <?php elseif (Yii::$app->user->can('handler')):?>
            <a class="btn purple" href="<?=Url::to(['order/index', 'handler_id' => '-1', 'status' => Order::STATUS_PENDING])?>">Chưa có người quản lý</a>
            <a class="btn purple" href="<?=Url::to(['order/index', 'handler_id' => Yii::$app->user->id])?>">Những đơn hàng của tôi</a>
            <?php elseif (Yii::$app->user->can('saler')):?>
            <a class="btn purple" href="<?=Url::to(['order/index', 'saler_id' => Yii::$app->user->id])?>">Những đơn hàng của tôi</a>
            <?php endif;?>
          </div>
          <?php if (Yii::$app->user->can('saler')) :?>
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['order/create', 'ref' => $ref])?>"><?=Yii::t('app', 'add_new')?></a>
          </div>
          <?php endif;?>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['order/index']]);?>     
            <?php $customer = $search->getCustomer();?>
            <?=$form->field($search, 'customer_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->customer_id) ? sprintf("%s - %s", $customer->username, $customer->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'customer_id'],
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

            <?php if (Yii::$app->user->can('admin')) :?>
            <?php $saler = $search->getSaler();?>
            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->saler_id) ? sprintf("%s - %s", $saler->username, $saler->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'saler_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên sale',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên sale')?>

            <?php $handler = $search->getHandler();?>
            <?=$form->field($search, 'handler_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($handler) ? sprintf("%s - %s", $handler->username, $handler->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'handler_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên đơn hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên đơn hàng')?>
            <?php elseif (Yii::$app->user->can('saler')):?>
              <?=$form->field($search, 'saler_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'saler_id']
              ])->hiddenInput()->label(false);?>
            <?php elseif (Yii::$app->user->can('handler')):?>
              <?=$form->field($search, 'handler_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'handler_id']
              ])->hiddenInput()->label(false);?>
            <?php endif;?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-2'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'status[]']
            ])->dropDownList($search->getStatus())->label('Trạng thái');?>

            <?php /*$form->field($search, 'start_date', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(DateRangePicker::className(), [
              'attributeTo' => 'end_date', 
              'labelTo' => '-',
              'form' => $form,
              'optionsTo' => ['name' => 'end_date', 'class' => 'form-control'],
              'options' => ['name' => 'start_date', 'class' => 'form-control'],
              'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                  'keepEmptyValues' => true,
                  'todayHighlight' => true
              ]
            ])->label('Ngày tạo'); */?>

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
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 20%;"> Tên khách hàng </th>
              <th style="width: 20%;"> Ngày tạo </th>
              <th style="width: 5%;"> Tổng Coin </th>
              <th style="width: 15%;"> Saler </th>
              <th style="width: 15%;"> Order Team </th>
              <th style="width: 10%;"> <?=Yii::t('app', 'status');?> </th>
              <th style="width: 10%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="8"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td style="vertical-align: middle;">Order #<?=$model->id;?></td>
                <td style="vertical-align: middle;"><?=$model->customer_name;?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                <td style="vertical-align: middle;">$<?=$model->total_price;?></td>
                <td style="vertical-align: middle;"><?=($model->saler) ? $model->saler->name : '';?></td>
                <td style="vertical-align: middle;"><?=($model->handler) ? $model->handler->name : '';?></td>
                <td style="vertical-align: middle;"><?=$model->status;?></td>
                <td style="vertical-align: middle;">
                  <?php
                  $canEdit = false;
                  if (Yii::$app->user->can('admin')) :
                    $canEdit = true;
                  elseif ($model->isVerifyingOrder()) :
                    $canEdit = Yii::$app->user->can('saler') && ($model->saler_id == Yii::$app->user->id);
                  elseif ($model->isPendingOrder()) :
                    $canEdit = Yii::$app->user->can('handler') && ($model->handler_id == Yii::$app->user->id);
                  endif; 
                  ?>
                  <a href='<?=Url::to(['order/view', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Xem"><i class="fa fa-eye"></i></a>
                  <?php if ($canEdit) :?>
                  <a href='<?=Url::to(['order/edit', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  <?php endif;?>
                  <?php if ($can_taken && !$model->handler_id) :?>
                  <a href='<?=Url::to(['order/taken', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa ajax-link tooltips" data-pjax="0" data-container="body" data-original-title="Nhận xử lý đơn hàng"><i class="fa fa-cogs"></i></a>
                  <?php endif;?>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".ajax-link").ajax_action({
  method: 'POST',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});


$("#aaa").daterangepicker({
  minDate: moment().subtract(2, 'years'),
  callback: function (startDate, endDate, period) {
    $(this).val(startDate.format('L') + ' – ' + endDate.format('L'));
  }
});
JS;
$this->registerJs($script);
?>