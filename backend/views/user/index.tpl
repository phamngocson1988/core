{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
<div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="/">Home</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <span>Manage Users</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Users</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Manage Users</span>
            </div>
            <div class="actions">
              <div class="btn-group btn-group-devided">
                <a class="btn green" href="{url route='user/create' ref=$ref}">Add new</a>
              </div>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row margin-bottom-10">
              <form method="GET">
                <div class="form-group col-md-3">
                  <label>Status: </label>
                  <select class="form-control" name="status">
                    <option value="">All</option>
                    {foreach $form->getUserStatus() as $statusKey => $statusLabel}
                    <option value="{$statusKey}" {if (string)$statusKey === $form->status} selected {/if}>{$statusLabel}</option>
                    {/foreach}
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Keyword: </label> <input type="search" class="form-control"
                    placeholder="Keyword" name="q" value="{$form->q}">
                </div>
                <div class="form-group col-md-3">
                  <button type="submit" class="btn btn-success table-group-action-submit"
                    style="margin-top:
                    25px;">
                  <i class="fa fa-check"></i> Search
                  </button>
                </div>
              </form>
            </div>
            {Pjax}
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th style="width: 5%;"> No </th>
                  <th style="width: 25%;"> Username </th>
                  <th style="width: 25%;"> Email </th>
                  <th style="width: 15%;"> Role </th>
                  <th style="width: 15%;"> Status </th>
                  <th style="width: 15%;" class="dt-center"> Actions </th>
                </tr>
              </thead>
              <tbody>
                {if $models}
                {foreach $models as $key => $model}
                <tr>
                  <td>{$key + $pages->offset + 1}</td>
                  <td>{$model->username}</td>
                  <td>{$model->email}</td>
                  <td>
                    {foreach $model->getRoles() as $role}
                    <span class="label label-info label-many">{$role}</span>
                    {/foreach}
                  </td>
                  <td>{$model->getStatusLabel()}</td>
                  <td>
                    {if $model->isActive()}
                    <a class="btn btn-xs grey-salsa delete-user" href="{url route='user/change-status' id=$model->id status='delete'}"><i class="fa fa-trash"></i></a>
                    {else}
                    <a class="btn btn-xs grey-salsa active-user" href="{url route='user/change-status' id=$model->id status='active'}"><i class="fa-check-square"></i></a>
                    {/if}
                    
                  </td>
                </tr>
                {/foreach}
                {else}
                <tr>
                  <td colspan="6">No data</td>
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
  </div>
</div>
<!-- END CONTENT BODY -->
{registerJs}
{literal}
$(".delete-user").ajax_action({
  confirm: true,
  confirm_text: 'Do you want to delete this user?',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-user").ajax_action({
  confirm: true,
  confirm_text: 'Do you want to enable this user?',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}