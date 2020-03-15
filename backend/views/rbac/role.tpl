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
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='rbac/create-role' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> {Yii::t('app', 'no')} </th>
              <th> Tên vai trò </th>
              <th> Mã vai trò </th>
              <th class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach array_values($models) as $key => $model}
            <tr>
              <td>{$key + 1}</td>
              <td>{$model->description}</td>
              <td>{$model->name}</td>
              <td>
                <a class="btn btn-sm grey-salsa tooltips" href="{url route='rbac/edit-role' name=$model->name}" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i> Chỉnh sửa</a>
                <a class="btn yellow-mint btn-sm tooltips" href="{url route='rbac/user-role' name=$model->name}" data-container="body" data-original-title="Danh sách nhân viên"><i class="fa fa-list"></i> Danh sách</a>
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="4">{Yii::t('app', 'no_data_found')}</td>
            </tr>
            {/if}
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>