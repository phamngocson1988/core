{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý mẫu nội dung</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý mẫu nội dung</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý mẫu nội dung</span>
        </div>
        <div class="actions">
          <div class="btn-template btn-template-devided">
            <a class="btn green" href="{url route='template/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
        </div>
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Yii::t('app', 'no')} </th>
              <th style="width: 20%;"> Tiêu đề </th>
              <th style="width: 50%;"> Nội dung </th>
              <th style="width: 10%;"> Status </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->title}</td>
              <td>{$model->content}</td>
              <td>{if $model->isActive()}Active{else}Disactive{/if}</td>
              <td>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='template/edit' id=$model->id}" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                <a class="btn btn-xs grey-salsa delete tooltips" href="{url route='template/delete' id=$model->id}" data-container="body" data-original-title="Xóa"><i class="fa fa-trash"></i></a>
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
$('.delete').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Do you really want to delete this template?',
  callback: function(data) {
    location.reload();
  },
});
{/literal}
{/registerJs}