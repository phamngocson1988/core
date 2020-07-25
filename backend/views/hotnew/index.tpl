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
      <span>Quản lý hot news</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý hot news</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý hot news</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='hotnew/create'}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> ID </th>
              <th> {Yii::t('app', 'image')} </th>
              <th> {Yii::t('app', 'title')} </th>
              <th> Đường dẫn </th>
              <th>  </th>
            </tr>
          </thead>
          <tbody>
              {if (!$models) }
              <tr><td colspan="5">{Yii::t('app', 'no_data_found')}</td></tr>
              {/if}
              {foreach $models as $model}
              <tr>
                <td style="vertical-align: middle;">{$model->id}</td>
                <td style="vertical-align: middle;"><img src="{$model->getImageUrl('100x100')}" width="120px;" /></td>
                <td style="vertical-align: middle;">{$model->title}</td>
                <td style="vertical-align: middle;">{$model->link}</td>
                <td style="vertical-align: middle;">
                  <a href='{url route="hotnew/edit" id=$model->id}' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
                  <a href='{url route="hotnew/delete" id=$model->id}' class="btn btn-xs grey-salsa delete-action"><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              {/foreach}
          </tbody>
        </table>
        {/Pjax}
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}
$(".delete-action").ajax_action({
  confirm: true,
  confirm_text: 'Do you want to delete this news?',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}