<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ImageInputWidget;
use common\widgets\MultipleImageInputWidget;
use yii\helpers\Url;
use common\components\helpers\FormatConverter;
use yii\web\JsExpression;

$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerCssFile('@web/vendor/assets/pages/css/profile.min.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/jquery.sparkline.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/pages/scripts/profile.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$showPrice = Yii::$app->user->can('orderteam');
$canUpdatePrice = Yii::$app->user->can('orderteam_manager');
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
<h1 class="page-title"> Danh sách nhà cung cấp </h1>
<!-- END PAGE TITLE-->

<div class="row">
  <div class="col-md-12">
    <div class="profile-sidebar">
      <?php $form = ActiveForm::begin(['action' => ['game/update-price', 'id' => $id], 'options' => ['class' => 'form-horizontal form-row-seperated form', 'id' => 'update-price-form']]);?>
      <div class="portlet light">
        <img id="image_game-image_id" class="img-responsive" src="<?=$model->getImageUrl('500x500');?>">
        <?php if ($canUpdatePrice) : ?>
        <?=$form->field($model, 'price1', [
          'options' => ['class' => 'list-separated profile-stat'],
          'parts' => ['{log}' => $lastPrice->old_price_1 ],
          'template' => '{label}<div class="flex-container" style="display: flex; flex-wrap: justify-content; justify-content: center; "><input type="text" disabled="" value="{log}" class="form-control">{input}</div>{hint}'
        ])->textInput()->label('Giá nhà cung cấp 1 (USD)');?>
        
        <?=$form->field($model, 'price2', [
          'options' => ['class' => 'list-separated profile-stat'],
          'parts' => ['{log}' => $lastPrice->old_price_2 ],
          'template' => '{label}<div class="flex-container" style="display: flex; flex-wrap: justify-content; justify-content: center; "><input type="text" disabled="" value="{log}" class="form-control">{input}</div>{hint}'
        ])->textInput()->label('Giá nhà cung cấp 2 (USD)');?>

        <?=$form->field($model, 'price3', [
          'options' => ['class' => 'list-separated profile-stat'],
          'parts' => ['{log}' => $lastPrice->old_price_3 ],
          'template' => '{label}<div class="flex-container" style="display: flex; flex-wrap: justify-content; justify-content: center; "><input type="text" disabled="" value="{log}" class="form-control">{input}</div>{hint}'
        ])->textInput()->label('Giá nhà cung cấp 3 (USD)');?>

        <?=Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn green']);?>
        <?=Html::resetButton(Yii::t('app', 'cancel'), ['class' => 'btn default']);?>
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
                    'inputOptions' => ['class' => 'form-control', 'name' => 'supplier_id']
                ])->dropdownList($search->getSuppliers(), ['prompt' => 'Chọn nhà cung cấp'])->label('Nhà cung cấp')?>
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

              <?php $numCols = 6;?>
              <?php if (!$showPrice) $numCols = $numCols - 2;?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-checkable">
                  <thead>
                    <tr>
                      <th> Mã nhà cung cấp </th>
                      <th> Nhà cung cấp </th>
                      <th <?=$showPrice ? '' : 'class="hide"';?>> Giá cũ (VNĐ) </th>
                      <th <?=$showPrice ? '' : 'class="hide"';?>> Giá hiện tại (VNĐ) </th>
                      <th> Số đơn hoàng thành </th>
                      <th> Tốc độ trung bình </th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php if (!$suppliers) : ?>
                      <tr><td colspan="<?=$numCols;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
                      <?php endif;?>
                      <?php foreach ($suppliers as $supplier) : ?>
                      <tr>
                        <td><?=$supplier->supplier_id;?></td>
                        <td><?=$supplier->user->name;?></td>
                        <th <?=$showPrice ? '' : 'class="hide"';?>><?=$supplier->old_price ? number_format($supplier->old_price) : '';?></td>
                        <th <?=$showPrice ? '' : 'class="hide"';?>><?=number_format($supplier->price);?></td>
                        <td><?=isset($countOrders[$supplier->supplier_id]) ? $countOrders[$supplier->supplier_id] : 0 ;?></td>
                        <td><?=isset($avgSpeeds[$supplier->supplier_id]) ? FormatConverter::countDuration(round($avgSpeeds[$supplier->supplier_id])) : FormatConverter::countDuration(0) ;?></td>
                        <td></td>
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
JS;
$redirect = Yii::getAlias('@web/vendor/assets/global/img');
// die($redirect);//vendor/assets/global/img
// $script = str_replace('###PATH###', $redirect, $script);
$this->registerJs($script);
?>