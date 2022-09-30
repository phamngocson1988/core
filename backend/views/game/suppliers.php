<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ImageInputWidget;
use common\widgets\MultipleImageInputWidget;
use yii\helpers\Url;
use common\components\helpers\FormatConverter;
use yii\web\JsExpression;
use common\components\helpers\StringHelper;
use common\models\CurrencySetting;

$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerCssFile('@web/vendor/assets/pages/css/profile.min.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/jquery.sparkline.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/pages/scripts/profile.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

// Currency
$usdCurrency = CurrencySetting::find()->where(['code' => 'USD'])->one();
$vndCurrency = CurrencySetting::find()->where(['code' => 'VND'])->one();

$showPrice = Yii::$app->user->can('orderteam');
$canUpdatePrice = Yii::$app->user->can('orderteam_manager');
$dateTimeFormat = 'd/m/y H:i';
$today = date($dateTimeFormat, strtotime('now'));
$old_price_1 = $old_price_2 = $old_price_3 = 0;
if (!count($lastPrices)) {
  $supplierPriceRangeTime = sprintf('%s - %s', $today, $today);
} elseif (count($lastPrices) == 1) {
  $lastPrice = reset($lastPrices);
  $lastPriceDate = date($dateTimeFormat, strtotime($lastPrice->updated_at));
  $supplierPriceRangeTime = sprintf('%s - %s', $lastPriceDate, $today);
} else {
  $newestPrice = $lastPrices[0];
  $lastPrice = $lastPrices[1];
  $newestPriceDate = date($dateTimeFormat, strtotime($newestPrice->updated_at));
  $lastPriceDate = date($dateTimeFormat, strtotime($lastPrice->updated_at));
  $supplierPriceRangeTime = sprintf('%s - %s', $lastPriceDate, $newestPriceDate);
  $old_price_1 = $newestPrice->old_price_1;
  $old_price_2 = $newestPrice->old_price_2;
  $old_price_3 = $newestPrice->old_price_3;
}
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="<?=Url::to(['game/edit', 'id' => $id]);?>">Cập nhật game</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['game/suppliers', 'id' => $id]);?>">Giá nhà cung cấp</a>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?=$model->getGame()->title;?> <strong>(Giá vốn: <?=StringHelper::numberFormat($usdCurrency->exchangeTo($model->getGame()->getCogs(), $vndCurrency), 2);?> VNĐ)</strong></h1>
<!-- END PAGE TITLE-->

<div class="row">
  <div class="col-md-12">
    <div class="profile-sidebar">
      <?php $form = ActiveForm::begin(['action' => ['game/update-price', 'id' => $id], 'options' => ['class' => 'form-horizontal form-row-seperated form', 'id' => 'update-price-form']]);?>
      <div class="portlet light">
        <img id="image_game-image_id" class="img-responsive" src="<?=$model->getGame()->getImageUrl('500x500');?>">
        <?php if ($canUpdatePrice) : ?>
        <?=$form->field($model, 'price1', [
          'options' => ['class' => 'list-separated profile-stat hide'],
          'labelOptions' => ['style' => 'font-weight: 900'],
          'parts' => ['{log}' => $old_price_1, '{hint}' => $supplierPriceRangeTime],
          'template' => '<strong>{label}</strong><div class="flex-container" style="display: flex; flex-wrap: justify-content; justify-content: center; "><input type="text" disabled="" value="{log}" class="form-control">{input}</div>{hint}'
        ])->textInput()->label('Giá nhà cung cấp 1 (USD)');?>
        
        <?=$form->field($model, 'price2', [
          'options' => ['class' => 'list-separated profile-stat hide'],
          'labelOptions' => ['style' => 'font-weight: 900'],
          'parts' => ['{log}' => $old_price_2, '{hint}' => $supplierPriceRangeTime],
          'template' => '{label}<div class="flex-container" style="display: flex; flex-wrap: justify-content; justify-content: center; "><input type="text" disabled="" value="{log}" class="form-control">{input}</div>{hint}'
        ])->textInput()->label('Giá nhà cung cấp 2 (USD)');?>

        <?=$form->field($model, 'price3', [
          'options' => ['class' => 'list-separated profile-stat hide'],
          'labelOptions' => ['style' => 'font-weight: 900'],
          'parts' => ['{log}' => $old_price_3, '{hint}' => $supplierPriceRangeTime],
          'template' => '{label}<div class="flex-container" style="display: flex; flex-wrap: justify-content; justify-content: center; "><input type="text" disabled="" value="{log}" class="form-control">{input}</div>{hint}'
        ])->textInput()->label('Giá nhà cung cấp 3 (USD)');?>

        <?=$form->field($model, 'reseller_price_amplitude', [
          'options' => ['class' => 'list-separated profile-stat'],
          'labelOptions' => ['style' => 'font-weight: 900'],
        ])->textInput();?>

        <?=$form->field($model, 'price_remark', [
          'options' => ['class' => 'list-separated profile-stat'],
          'labelOptions' => ['style' => 'font-weight: 900'],
          'inputOptions' => ['style' => 'resize: vertical', 'class' => 'form-control']
        ])->textArea();?>

        <?=Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn green']);?>
        <?=Html::a(Yii::t('app', 'cancel'), Url::to(['game/index']), ['class' => 'btn default']);?>
        <?php endif;?>
      </div>
      <?php ActiveForm::end()?>
    </div>
    <!-- END BEGIN PROFILE SIDEBAR -->
    <!-- BEGIN PROFILE CONTENT -->
    <div class="profile-content">
      <div class="row">
        <div class="col-md-12">
          <div class="portlet light ">
            <div class="portlet-body">
              <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['game/suppliers', 'id' => $id]]);?>
              <div class="row margin-bottom-10">
                <?=$form->field($search, 'supplier_id', [
                  'options' => ['class' => 'form-group col-md-4 col-lg-3'],
                ])->widget(kartik\select2\Select2::classname(), [
                  'data' => $search->getSuppliers(),
                  'options' => ['class' => 'form-control'],
                  'pluginOptions' => [
                    'placeholder' => 'Chọn nhà cung cấp',
                  ]
                ])->label('Nhà cung cấp');?>

                <?php if ($showPrice) : ?>
                <?=$form->field($search, 'price_from', [
                  'options' => ['class' => 'form-group col-md-1 col-lg-1'],
                  'inputOptions' => ['class' => 'form-control', 'name' => 'price_from']
                ])->textInput()->label('Giá từ');?>

                <?=$form->field($search, 'price_to', [
                  'options' => ['class' => 'form-group col-md-1 col-lg-1'],
                  'inputOptions' => ['class' => 'form-control', 'name' => 'price_to']
                ])->textInput()->label('Giá đến');?>
                <?php endif;?>
                <?=$form->field($search, 'speed_from', [
                  'options' => ['class' => 'form-group col-md-1 col-lg-1'],
                  'inputOptions' => ['class' => 'form-control', 'name' => 'speed_from']
                ])->textInput()->label('Tốc độ từ');?>

                <?=$form->field($search, 'speed_to', [
                  'options' => ['class' => 'form-group col-md-1 col-lg-1'],
                  'inputOptions' => ['class' => 'form-control', 'name' => 'speed_to']
                ])->textInput()->label('Tốc độ đến');?>

                <div class="form-group col-md-2 col-lg-2">
                  <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                    <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
                  </button>
                </div>
              </div>
              <?php ActiveForm::end()?>

              <?php $numCols = 10;?>
              <?php if (!$showPrice) $numCols = $numCols - 2;?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-checkable">
                  <thead>
                    <tr>
                      <th> Mã nhà cung cấp </th>
                      <th> Nhà cung cấp </th>
                      <th <?=$showPrice ? '' : 'class="hide"';?>> Giá cũ (VNĐ) </th>
                      <th <?=$showPrice ? '' : 'class="hide"';?>> Giá hiện tại (VNĐ) </th>
                      <th> TG Nạp </th>
                      <th>Tổng TG NCC hoàn thành</th>
                      <th> Tổng đơn / Tổng gói </th>
                      <th> Đơn/lượt </th>
                      <th> PPTĐ </th>
                      <th> Đăng ký </th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php if (!$suppliers) : ?>
                      <tr><td colspan="<?=$numCols;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
                      <?php endif;?>
                      <?php foreach ($suppliers as $supplier) : ?>
                      <tr>
                        <td class="center"><?=$supplier->supplier_id;?></td>
                        <td class="left"><?=$supplier->user->name;?></td>
                        <td <?=$showPrice ? 'class="center"' : 'class="hide"';?>>
                          <div><?=$supplier->old_price ? number_format($supplier->old_price) : '';?></div>
                          <div style="font-size: 12px; font-style: italic;"><?=$supplier->last_updated_price_at ? date('d/m/y H:i', strtotime($supplier->last_updated_price_at)) : '';?></div>
                        </td>
                        <td <?=$showPrice ? 'class="center"' : 'class="hide"';?>>
                          <div><?=number_format($supplier->price);?></div>
                          <div style="font-size: 12px; font-style: italic;"><?=$supplier->updated_price_at ? date('d/m/y H:i', strtotime($supplier->updated_price_at)) : '';?></div>
                        </td>
                        <td class="center"><?=$supplier->last_speed ?  number_format($supplier->last_speed) : '-';?></td>
                        <td class="center"><?=$supplier->last_completing_time ?  number_format($supplier->last_completing_time) : '-';?></td>
                        <td class="center"><?=isset($countOrders[$supplier->supplier_id]) ? $countOrders[$supplier->supplier_id] : 0 ;?> / <?=isset($sumQuantity[$supplier->supplier_id]) ? StringHelper::numberFormat($sumQuantity[$supplier->supplier_id], 2) : 0 ;?></td>
                        <td class="center">
                          <a href='<?=Url::to(['game/max-order', 'supplier_id' => $supplier->supplier_id, 'game_id' => $supplier->game_id]);?>' data-target="#update-max-order" class="tooltips" data-pjax="0" data-container="body" data-original-title="Cập nhật số đơn / lượt" data-toggle="modal" ><?=$supplier->max_order ? number_format($supplier->max_order) : '-';?></a>
                        </td>
                        <td class="center">
                          <?php if ($supplier->isEnabled()) : ?>
                            <?php if ($supplier->isAutoDispatcher()) : ?>
                            <a href="<?=Url::to(['supplier-game/dispatcher', 'game_id' => $id, 'supplier_id' => $supplier->supplier_id, 'action' => 'off']);?>" class="btn btn-sm green dispatcher-action tooltips action-link" data-container="body" data-original-title="Tạm ngưng PPTĐ"><i class="fa fa-power-off"></i></a>
                            <?php else :?>
                            <a href="<?=Url::to(['supplier-game/dispatcher', 'game_id' => $id, 'supplier_id' => $supplier->supplier_id, 'action' => 'on']);?>" class="btn btn-sm default dispatcher-action tooltips action-link" data-container="body" data-original-title="Kích hoạt PPTĐ"><i class="fa fa-power-off"></i></a>
                            <?php endif;?>
                          <?php else :?>
                          <a href="javascript:;" class="btn btn-sm default tooltips" data-container="body" data-original-title="Không thể kích hoạt PPTĐ"><i class="fa fa-power-off"></i></a>
                          <?php endif;?>
                        </td>
                        <td class="center">
                          <?php if ($supplier->isEnabled()) : ?>
                          <a href="<?=Url::to(['supplier-game/disable', 'game_id' => $id, 'supplier_id' => $supplier->supplier_id]);?>" class="btn btn-sm green link-action tooltips action-link" data-container="body" data-original-title="Tạm ngưng game"><i class="fa fa-power-off"></i></a>
                          <?php else :?>
                          <a href="<?=Url::to(['supplier-game/enable', 'game_id' => $id, 'supplier_id' => $supplier->supplier_id]);?>" class="btn btn-sm default link-action tooltips action-link" data-container="body" data-original-title="Kích hoạt game"><i class="fa fa-power-off"></i></a>
                          <?php endif;?>
                        </td>
                      </tr>
                      <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="update-max-order" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php
$script = <<< JS
App.setAssetsPath('/vendor/assets/');
var sendForm = new AjaxFormSubmit({element: '#update-price-form'});
sendForm.beforeSend = function(form) {
  App.blockUI({
      target: '#update-price-form',
      overlayColor: '#000'
  });
};
sendForm.success = function(data, form) {
  // App.unblockUI('#update-price-form');
  // toastr.success("Cập nhật thành công");
  location.reload();
}
// enable/disable game
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
//dispatcher
$(".dispatcher-action").ajax_action({
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
      alert(errors);
  },
});

// supplier
$(document).on('submit', 'body .update-max-order-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      if (!result.status)
       // alert(result.error);
      toastr.error(result.error); 
      else 
        location.reload();
    },
  });
  return false;
});
JS;
$redirect = Yii::getAlias('@web/vendor/assets/global/img');
// die($redirect);//vendor/assets/global/img
// $script = str_replace('###PATH###', $redirect, $script);
$this->registerJs($script);
?>