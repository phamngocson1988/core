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
      <span>Quản lý nhóm danh bạ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý nhóm danh bạ</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý nhóm danh bạ</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='group/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
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
              <th style="width: 20%;"> Name </th>
              <th style="width: 40%;"> Mô tả </th>
              <th style="width: 10%;"> Num of contacts </th>
              <th style="width: 10%;"> Status </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->name}</td>
              <td>{$model->description}</td>
              <td>{$model->getNumberContacts()}</td>
              <td>{if $model->isActive()}Active{else}Disactive{/if}</td>
              <td>
                <a class="btn btn-xs grey-salsa" href="{url route='group/edit' id=$model->id}"><i class="fa fa-edit"></i></a>
                {if !$model->getNumberContacts()}
                <a class="btn btn-xs grey-salsa delete" href="{url route='group/delete' id=$model->id}"><i class="fa fa-trash"></i></a>
                {/if}
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="6">{Yii::t('app', 'no_data_found')}</td>
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
  confirm_text: 'Do you really want to delete this group?',
  callback: function(data) {
    location.reload();
  },
});
{/literal}
{/registerJs}