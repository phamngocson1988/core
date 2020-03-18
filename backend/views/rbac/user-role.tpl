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
      <span>Danh sách nhân viên</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Danh sách nhân viên có vai trò <strong>{$role->name}</strong></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='/rbac/assign-role'}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
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
                <button type="button" class="btn green btn-outline">{$role->name}</button>
              </td>
              <td>{$model->getStatusLabel()}</td>
              <td>
                {if $app->user->id != $model->id}
                <a class="btn btn-sm grey-salsa popovers" href="{url route='/rbac/revoke-role' user_id=$model->id role=$role->name}" data-toggle="modal" data-target="#revoke-role-modal" data-container="body" data-trigger="hover" data-content="Loại bỏ vài trò '{$role->name}' khỏi nhân viên {$model->name}"><i class="fa fa-ban"></i> Loại bỏ</a>
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

<!-- Revoke modal -->
<div class="modal fade" id="revoke-role-modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>

{registerJs}
{literal}
// supplier
$(document).on('submit', 'body #revoke-role-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      if (!result.status) {
        toastr.alert(result.error);
        return false;

      } else {
        toastr.success('Thực hiện thành công');
        location.reload();
      }
    },
  });
  return false;
});
{/literal}
{/registerJs}