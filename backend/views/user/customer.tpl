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
      <span>Quản lý khách hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý khách hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý khách hàng</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='user/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-3">
              <label>{Yii::t('app', 'status')}: </label>
              <select class="form-control" name="status">
                <option value="">All</option>
                {foreach $form->getUserStatus() as $statusKey => $statusLabel}
                <option value="{$statusKey}" {if (string)$statusKey === $form->status} selected {/if}>{$statusLabel}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>{Yii::t('app', 'keyword')}: </label> <input type="search" class="form-control"
                placeholder="{Yii::t('app', 'keyword')}" name="q" value="{$form->q}">
            </div>
            <div class="form-group col-md-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> {Yii::t('app', 'search')}
              </button>
            </div>
          </form>
        </div>
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Yii::t('app', 'no')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'name')} </th>
              <th style="width: 15%;"> Tên đăng nhập </th>
              <th style="width: 20%;"> {Yii::t('app', 'email')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'status')} </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->name}</td>
              <td>{$model->username}</td>
              <td>{$model->email}</td>
              <td>{$model->getStatusLabel()}</td>
              <td>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='user/edit' id=$model->id}" data-container="body" data-original-title="{Yii::t('app', 'edit_user')}"><i class="fa fa-pencil"></i></a>
                {if $app->user->id != $model->id}
                {if $model->isActive()}
                <a class="btn btn-xs grey-salsa delete-user tooltips" href="{url route='user/change-status' id=$model->id status='delete'}" data-container="body" data-original-title="{Yii::t('app', 'disable_user')}"><i class="fa fa-minus-circle"></i></a>
                {else}
                <a class="btn btn-xs grey-salsa active-user tooltips" href="{url route='user/change-status' id=$model->id status='active'}" data-container="body" data-original-title="{Yii::t('app', 'enable_user')}"><i class="fa fa-check-square"></i></a>
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
        {LinkPager::widget(['pagination' => $pages])}
        {/Pjax}
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}
$(".delete-user").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_disable_user')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-user").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_enable_user')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}