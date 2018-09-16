<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'manage_roles')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_roles')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('app', 'manage_roles')}</span>
        </div>
        {if $app->user->can('admin')}
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='rbac/create-role' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
        {/if}
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 30%;"> {Yii::t('app', 'role')} </th>
              <th style="width: 30%;"> {Yii::t('app', 'description')} </th>
              <th style="width: 20%;"> {Yii::t('app', 'count')} </th>
              <th style="width: 20%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $model}
            <tr>
              <td>{$model->name}</td>
              <td>{$model->description}</td>
              <td>{count(Yii::$app->authManager->getUserIdsByRole($model->name))}</td>
              <td>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='rbac/user-role' name=$model->name}" data-container="body" data-original-title="{Yii::t('app', 'edit')}"><i class="fa fa-edit"></i></a>
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="3">{Yii::t('app', 'no_data_found')}</td>
            </tr>
            {/if}
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>