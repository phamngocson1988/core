{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='yii\helpers\Html'}
{$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']])}
{$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset'])}
{$this->registerJsFile('@web/vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset'])}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý danh bạ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý danh bạ</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý danh bạ</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green btn-outline sbold" href="{url route='contact/download'}">Download Template</a>
            <a class="btn red btn-outline sbold" id="csv_upload">Import</a>
            <input type="file" id="file_upload" name="file" style="display: none" accept="csv" />
            <a class="btn green" href="{url route='contact/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          {ActiveForm assign='form' method='get'}
            {$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput()->label('Keyword')}
            {$form->field($search, 'group_ids', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'group_ids[]']
            ])->dropDownList($search->fetchGroups())->label('Nhóm danh bạ')}
            <div class="form-group col-md-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> {Yii::t('app', 'search')}
              </button>
            </div>
          {/ActiveForm}
        </div>
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Html::checkbox('checkall')} <button id="deleteall">Delete All</button></th>
              <th style="width: 5%;"> {Yii::t('app', 'no')} </th>
              <th style="width: 10%;"> Tên </th>
              <th style="width: 10%;"> Số điện thoại </th>
              <th style="width: 10%;"> Mô tả </th>
              <th style="width: 20%;"> Nhóm </th>
              <th style="width: 5%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td> {Html::checkbox('id[]', false, ['value' => $model->id])}</td>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->name}</td>
              <td>{$model->phone}</td>
              <td>{$model->description}</td>
              <td>
              {foreach $model->groups as $group}
              <span class="label label-default">{$group->name}</span> 
              {/foreach}
              </td>
              <td>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='contact/edit' id=$model->id}" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                <a class="btn btn-xs grey-salsa delete tooltips" href="{url route='contact/delete' id=$model->id}" data-container="body" data-original-title="Xóa"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="5">{Yii::t('app', 'no_data_found')}</td>
            </tr>
            {/if}
          </tbody>
        </table>
        {LinkPager::widget(['pagination' => $pages])}
        {/Pjax}
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}
var csvUpload = new AjaxUploadFile({
  trigger_element: '#csv_upload', 
  file_element: '#file_upload',
  link_upload: '{/literal}{url route='file/ajax-upload'}{literal}'
});
csvUpload.callback = function(result) {
  var file = result[0];
  window.location.href = "{/literal}{url route='contact/import'}{literal}" + "?id=" + file.id;
}
$(".delete").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'Bạn có muốn xóa liên hệ này không?')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
$("input[name='id[]']:checked")
$('#deleteall').on('click', function() {
  var ids = [];
  $.each($("input[name='id[]']:checked"), function(){
    ids.push($(this).val());
  });
  $.ajax({
    url: this.options.link_upload,
    type: 'POST',
    processData: false, // important
    contentType: false, // important
    dataType : 'json',
    data: this.form,
    success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            alert(result.errors.join("\n"));
            return false;
        } else {
            that.callback(result.data);
        }
          
    },
  });
})
{/literal}
{/registerJs}