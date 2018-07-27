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
      <span>{Yii::t('app', 'manage_customers')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_customers')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('app', 'manage_customers')}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='customer/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-3">
              <label>{Yii::t('app', 'status')}: </label>
              <select class="form-control" name="status">
                <option value="">{Yii::t('app', 'all')}</option>
                {foreach $form->getUserStatus() as $statusKey => $statusLabel}
                <option value="{$statusKey}" {if (string)$statusKey === $form->status} selected {/if}>{$statusLabel}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>{Yii::t('app', 'keyword')}: </label> <input type="search" class="form-control"
                placeholder="Keyword" name="q" value="{$form->q}">
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
              <th style="width: 25%;"> Username </th>
              <th style="width: 15%;"> {Yii::t('app', 'status')} </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->username}</td>
              <td>{$model->getStatusLabel()}</td>
              <td>
                {if $model->isActive()}
                <a class="btn btn-xs grey-salsa delete-customer" href="{url route='customer/change-status' id=$model->id status='delete'}"><i class="fa fa-toggle-off"></i></a>
                {else}
                <a class="btn btn-xs grey-salsa active-customer" href="{url route='customer/change-status' id=$model->id status='active'}"><i class="fa fa-toggle-on"></i></a>
                {/if}
                
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
        {LinkPager::widget(['pagination' => $pages])}
        {/Pjax}
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}
$(".delete-customer").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_delete_customer')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-customer").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_enable_customer')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}