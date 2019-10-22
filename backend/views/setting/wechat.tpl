{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
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
      <span>Wechat</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thiết lập cho Wechat</h1>
<!-- END PAGE TITLE-->
{ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="actions btn-set">
          <a href="{url route='setting/list-offline'}" class="btn default"><i class="fa fa-angle-left"></i> Danh sách giao dịch chuyển khoản</a>
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> {Yii::t('app', 'save')}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Thông tin tài khoản wechat</div>
  <div class="panel-body">
    <div class="row">
        {$form->field($model, 'content', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'inputOptions' => ['id' => 'content', 'class' => 'form-control'],
          'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
        ])->widget(TinyMce::className(), [
          'options' => ['rows' => 20]
        ])}
        {$form->field($model, 'logo', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'template' => '{label}<div class="col-md-6"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="logo_upload">Upload</button><input type="file" id="file_logo_upload" style="display: none" name="file" /></span>{hint}{error}</div></div>',
          'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'logo']
        ])->textInput()}
        {$form->field($model, 'logo_width', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
          'inputOptions' => ['class' => 'form-control']
        ])->textInput()}
        {$form->field($model, 'logo_height', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
          'inputOptions' => ['class' => 'form-control']
        ])->textInput()}
    </div>
  </div>
</div>
{/ActiveForm}
{registerJs}
{literal}
var logoUpload = new AjaxUploadFile({
  trigger_element: '#logo_upload', 
  file_element: '#file_logo_upload',
  link_upload: '{/literal}{url route="file/ajax-upload"}{literal}'
});
logoUpload.callback = function(result) {
  var file = result[0];
  console.log(file);
  $('#logo').val(file.src);
}
{/literal}
{/registerJs}