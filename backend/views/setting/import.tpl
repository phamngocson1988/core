{use class='yii\widgets\ActiveForm' type='block'}
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="javascript:;">{Yii::t('app', 'settings')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Import Settings</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Import Settings</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Import Settings</div>
        <div class="actions btn-set">
          <button type="reset" class="btn default">
          <i class="fa fa-angle-left"></i> {Yii::t('app', 'reset')}
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> {Yii::t('app', 'save')}
          </button>
        </div>
      </div>
      <div class="portlet-body">
        <div class="tabbable-bordered">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_general" data-toggle="tab"> {Yii::t('app', 'main_content')} </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_general">
              <div class="form-body">
                {$form->field($model, 'import_reseller_template', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="csv_upload">Upload</button><input type="file" id="file_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
                  'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'csv_url']
                ])->textInput()}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {/ActiveForm}
  </div>
</div>
{registerJs}
{literal}
var csvUpload = new AjaxUploadFile({
  trigger_element: '#csv_upload', 
  file_element: '#file_upload',
  link_upload: '{/literal}{url route="file/ajax-upload"}{literal}'
});
csvUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#csv_url').val(file.src);
}
{/literal}
{/registerJs}