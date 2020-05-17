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
                <?=$form->field($model, 'logo', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'logo'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="logo_upload">Upload</button><input type="file" id="file_logo_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
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
$script = <<< JS
var logoUpload = new AjaxUploadFile({
  trigger_element: '#logo_upload', 
  file_element: '#file_logo_upload',
  link_upload: '###LINK###'
});
logoUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#logo').val(file.src);
}
JS;
$uploadLink = Url::to(['file/ajax-upload']);
$script = str_replace('###LINK###', $uploadLink, $script);
$this->registerJs($script);
?>