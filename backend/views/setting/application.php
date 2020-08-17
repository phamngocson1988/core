<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="javascript:;"><?=Yii::t('app', 'settings');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'application');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'application_settings');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption"><?=Yii::t('app', 'application_settings');?></div>
        <div class="actions btn-set">
          <button type="reset" class="btn default">
          <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'reset');?>
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> <?=Yii::t('app', 'save');?>
          </button>
        </div>
      </div>
      <div class="portlet-body">
        <div class="tabbable-bordered">
          <?php echo $this->render('@backend/views/setting/_widget_tabs.php', ['tab' => 'application']);?>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_general">
              <div class="form-body">
                <?=$form->field($model, 'admin_email', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'contact_phone', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'contact_email', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'customer_service_email', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'supplier_service_email', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'accountant_email', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'exchange_rate_cny', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'exchange_rate_vnd', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'managing_cost_rate', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'investing_cost_rate', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'desired_profit', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'reseller_desired_profit', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'logo', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'logo'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="logo_upload">Upload</button><input type="file" id="file_logo_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                ])->textInput();?>

                <?=$form->field($model, 'affiliate_banner', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'affiliate_banner'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="affiliate_banner_upload">Upload</button><input type="file" id="file_affiliate_banner_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                ])->textInput();?>
                <?=$form->field($model, 'affiliate_banner_mobile', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'affiliate_banner_mobile'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="affiliate_banner_mobile_upload">Upload</button><input type="file" id="file_affiliate_banner_mobile_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                ])->textInput();?>
                <?=$form->field($model, 'affiliate_banner_link', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'refer_banner', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'refer_banner'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="refer_banner_upload">Upload</button><input type="file" id="file_refer_banner_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                ])->textInput();?>
                <?=$form->field($model, 'refer_banner_mobile', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'refer_banner_mobile'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="refer_banner_mobile_upload">Upload</button><input type="file" id="file_refer_banner_mobile_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                ])->textInput();?>
                <?=$form->field($model, 'refer_banner_link', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>

                <?=$form->field($model, 'kcoin_banner', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'kcoin_banner'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="kcoin_banner_upload">Upload</button><input type="file" id="file_kcoin_banner_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                ])->textInput();?>
                <?=$form->field($model, 'kcoin_banner_mobile', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'kcoin_banner_mobile'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="kcoin_banner_mobile_upload">Upload</button><input type="file" id="file_kcoin_banner_mobile_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                ])->textInput();?>
                <?=$form->field($model, 'kcoin_banner_link', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput();?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end();?>
  </div>
</div>
<?php
$uploadLink = Url::to(['file/ajax-upload']);
$script = <<< JS
var logoUpload = new AjaxUploadFile({
  trigger_element: '#logo_upload', 
  file_element: '#file_logo_upload',
  link_upload: '$uploadLink'
});
logoUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#logo').val(file.src);
}

var affUpload = new AjaxUploadFile({
  trigger_element: '#affiliate_banner_upload', 
  file_element: '#file_affiliate_banner_upload',
  link_upload: '$uploadLink'
});
affUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#affiliate_banner').val(file.src);
}

var affMobileUpload = new AjaxUploadFile({
  trigger_element: '#affiliate_banner_mobile_upload', 
  file_element: '#file_affiliate_banner_mobile_upload',
  link_upload: '$uploadLink'
});
affMobileUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#affiliate_banner_mobile').val(file.src);
}

var referUpload = new AjaxUploadFile({
  trigger_element: '#refer_banner_upload', 
  file_element: '#file_refer_banner_upload',
  link_upload: '$uploadLink'
});
referUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#refer_banner').val(file.src);
}

var referMobileUpload = new AjaxUploadFile({
  trigger_element: '#refer_banner_mobile_upload', 
  file_element: '#file_refer_banner_mobile_upload',
  link_upload: '$uploadLink'
});
referMobileUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#refer_banner_mobile').val(file.src);
}

var kcoinBannerUpload = new AjaxUploadFile({
  trigger_element: '#kcoin_banner_upload', 
  file_element: '#file_kcoin_banner_upload',
  link_upload: '$uploadLink'
});
kcoinBannerUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#kcoin_banner').val(file.src);
}

var kcoinBannerMobileUpload = new AjaxUploadFile({
  trigger_element: '#kcoin_banner_mobile_upload', 
  file_element: '#file_kcoin_banner_mobile_upload',
  link_upload: '$uploadLink'
});
kcoinBannerMobileUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#kcoin_banner_mobile').val(file.src);
}
JS;
$this->registerJs($script);
?>