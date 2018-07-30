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
      <a href="/rbac/role">{Yii::t('app', 'manage_roles')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'users')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'users')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {$role->name}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='/rbac/assign-role' ref=$ref role=$role->name}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Yii::t('app', 'no')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'name')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'username')} </th>
              <th style="width: 20%;"> {Yii::t('app', 'email')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'role')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'status')} </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + 1}</td>
              <td>{$model->name}</td>
              <td>{$model->username}</td>
              <td>{$model->email}</td>
              <td>
                <span class="label label-info label-many">{$role->name}</span>
              </td>
              <td>{$model->getStatusLabel()}</td>
              <td>
                {if $app->user->id != $model->id}
                <a class="btn btn-xs grey-salsa revoke-user popovers" href="{url route='/rbac/revoke-role' user_id=$model->id role=$role->name}" data-container="body" data-trigger="hover" data-content="Remove '{$role->name}' role from {$model->name}"><i class="fa fa-ban"></i></a>
                {/if}
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="7">{Yii::t('app', 'no_data_found')}</td>
            </tr>
            {/if}
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
$(".revoke-user").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_revoke_user')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}