<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Nhà quản trị</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý nhân viên</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='user/create' ref=$ref}">Thêm mới</a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> {Yii::t('app', 'no')} </th>
              <th> {Yii::t('app', 'name')} </th>
              <th> {Yii::t('app', 'username')} </th>
              <th> {Yii::t('app', 'email')} </th>
              <th> {Yii::t('app', 'role')} </th>
              <th> {Yii::t('app', 'status')} </th>
              <th class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $model}
            <tr>
              <td>{$model->id}</td>
              <td>{$model->name}</td>
              <td>{$model->username}</td>
              <td>{$model->email}</td>
              <td>
                {foreach $model->getRoles() as $role => $roleName}
                <a type="button" class="btn green btn-outline" href="{url route='rbac/user-role' name=$role}">{$roleName}</a>
                {/foreach}
              </td>
              <td>{$model->getStatusLabel()}</td>
              <td>
                <a class="btn btn-sm grey-salsa tooltips" href="{url route='user/edit' id=$model->id}" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i> Chỉnh sửa</a>
                {if $app->user->id != $model->id}
                {if $model->isActive()}
                <a class="btn btn-sm grey-salsa delete-user tooltips" href="{url route='user/change-status' id=$model->id status='delete'}" data-container="body" data-original-title="Tạm khóa"><i class="fa fa-minus-circle"></i> Tạm khóa</a>
                {else}
                <a class="btn btn-sm grey-salsa active-user tooltips" href="{url route='user/change-status' id=$model->id status='active'}" data-container="body" data-original-title="Kích hoạt"><i class="fa fa-check-square"></i> Kích hoạt</a>
                {/if}
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
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}
$(".delete-user").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có muốn tạm ẩn nhân viên này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-user").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có muốn kích hoạt lại nhân viên này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}