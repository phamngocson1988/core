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
      <span>Quản lý bộ số</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý bộ số</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý bộ số</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='dialer/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
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
              <th style="width: 20%;"> Phone number </th>
              <th style="width: 10%;"> Extend </th>
              <th style="width: 20%;"> Domain </th>
              <th style="width: 10%;"> Action </th>
              <th style="width: 10%;"> Num of users </th>
              <th style="width: 10%;"> Status </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->number}</td>
              <td>{$model->extend}</td>
              <td>{$model->domain}</td>
              <td>{$model->action}</td>
              <td>{$model->getNumberUsers()}</td>
              <td>{if $model->isActive()}Active{else}Disactive{/if}</td>
              <td>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='dialer/edit' id=$model->id}" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                {if !$model->getNumberUsers()}
                <a class="btn btn-xs grey-salsa delete tooltips" href="{url route='dialer/delete' id=$model->id}" data-container="body" data-original-title="Xóa"><i class="fa fa-trash"></i></a>
                {/if}
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="8">{Yii::t('app', 'no_data_found')}</td>
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
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Do you really want to delete this dialer?',
  callback: function(data) {
    location.reload();
  },
});
{/literal}
{/registerJs}