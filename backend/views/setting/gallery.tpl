{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\MultipleImageInputWidget'}
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
      <span>{Yii::t('app', 'application')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Banner trang chủ</h1>
<!-- END PAGE TITLE-->
{ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Hình ảnh</div>
        <div class="actions btn-set">
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> {Yii::t('app', 'save')}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Hình 1</div>
  <div class="panel-body">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="thumbnail">
            <img id="image1" src="{if ($model->link1)}{$model->link1}{else}https://cdn.stylepark.com/manufacturers/h/hi-macs/produkte/solid-steel-grey/solid-steel-grey-1.jpg{/if}" alt="100%x200" style="width: 100%; height: 200px; display: block;">
            <input type="file" id="file_upload1" name="file_upload1" style="display: none" />
        </div>
      </div>
      <div class="col-sm-12 col-md-9">
        <div class="row">
          {$form->field($model, 'title1', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'content1', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'link1', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="remove1">Remove</button></span>{error}</div></div>',
            'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'link1']
          ])->textInput()}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Hình 2</div>
  <div class="panel-body">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="thumbnail">
            <img id="image2" src="{if ($model->link2)}{$model->link2}{else}https://cdn.stylepark.com/manufacturers/h/hi-macs/produkte/solid-steel-grey/solid-steel-grey-1.jpg{/if}" alt="100%x200" style="width: 100%; height: 200px; display: block;">
            <input type="file" id="file_upload2" name="file_upload2" style="display: none" />
        </div>
      </div>
      <div class="col-sm-12 col-md-9">
        <div class="row">
          {$form->field($model, 'title2', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'content2', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'link2', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="remove2">Remove</button></span>{error}</div></div>',
            'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'link2']
          ])->textInput()}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Hình 3</div>
  <div class="panel-body">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="thumbnail">
            <img id="image3" src="{if ($model->link3)}{$model->link3}{else}https://cdn.stylepark.com/manufacturers/h/hi-macs/produkte/solid-steel-grey/solid-steel-grey-1.jpg{/if}" alt="100%x200" style="width: 100%; height: 200px; display: block;">
            <input type="file" id="file_upload3" name="file_upload3" style="display: none" />
        </div>
      </div>
      <div class="col-sm-12 col-md-9">
        <div class="row">
          {$form->field($model, 'title3', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'content3', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'link3', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="remove3">Remove</button></span>{error}</div></div>',
            'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'link3']
          ])->textInput()}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Hình 4</div>
  <div class="panel-body">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="thumbnail">
            <img id="image4" src="{if ($model->link4)}{$model->link4}{else}https://cdn.stylepark.com/manufacturers/h/hi-macs/produkte/solid-steel-grey/solid-steel-grey-1.jpg{/if}" alt="100%x200" style="width: 100%; height: 200px; display: block;">
            <input type="file" id="file_upload4" name="file_upload4" style="display: none" />
        </div>
      </div>
      <div class="col-sm-12 col-md-9">
        <div class="row">
          {$form->field($model, 'title4', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'content4', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput()}
          {$form->field($model, 'link4', [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="remove4">Remove</button></span>{error}</div></div>',
            'inputOptions' => ['class' => 'form-control', 'readonly' => true, 'id' => 'link4']
          ])->textInput()}
        </div>
      </div>
    </div>
  </div>
</div>
{/ActiveForm}
{registerJs}
{literal}
var upload1 = new AjaxUploadFile({trigger_element: '#image1', file_element: '#file_upload1'});
upload1.callback = function(result) {
  $('#image1').attr('src', result[0].src);
  $('#link1').val(result[0].src)
}

var upload2 = new AjaxUploadFile({trigger_element: '#image2', file_element: '#file_upload2'});
upload2.callback = function(result) {
  $('#image2').attr('src', result[0].src);
  $('#link2').val(result[0].src)
}

var upload3 = new AjaxUploadFile({trigger_element: '#image3', file_element: '#file_upload3'});
upload3.callback = function(result) {
  $('#image3').attr('src', result[0].src);
  $('#link3').val(result[0].src)
}

var upload4 = new AjaxUploadFile({trigger_element: '#image4', file_element: '#file_upload4'});
upload4.callback = function(result) {
  $('#image4').attr('src', result[0].src);
  $('#link4').val(result[0].src)
}
$('#remove1').on('click', function(){
  $('#link1').val('');
});
$('#remove2').on('click', function(){
  $('#link2').val('');
});
$('#remove3').on('click', function(){
  $('#link3').val('');
});
$('#remove4').on('click', function(){
  $('#link4').val('');
});
{/literal}
{/registerJs}