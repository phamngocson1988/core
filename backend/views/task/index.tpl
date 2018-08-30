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
      <span>{Yii::t('app', 'manage_tasks')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_tasks')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('app', 'manage_tasks')}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='task/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">            
            <div class="form-group col-md-3">
              <label>{Yii::t('app', 'creator')}: </label> 
              <select class="form-control find-user" name="created_by" data-allow-clear="true" data-placeholder="{Yii::t('app', 'all')}">
                {if ($form->created_by)}
                <option value="{$form->created_by}" selected="selected">{$creator->username} - {$creator->email}</option>
                {/if}
              </select>
            </div>
            <div class="form-group col-md-3">
              <label>{Yii::t('app', 'assignee')}: </label> 
              <select class="form-control find-user" name="assignee" data-allow-clear="true" data-placeholder="{Yii::t('app', 'all')}">
                {if ($form->assignee)}
                <option value="{$form->assignee}" selected="selected">{$assignee->username} - {$assignee->email}</option>
                {/if}
              </select>
            </div>
            <div class="form-group col-md-3">
              <label>{Yii::t('app', 'status')}: </label> 
              <select class="form-control" name="status">
                <option value="">{Yii::t('app', 'all')}</option>
                {foreach $form->getStatus() as $statusKey => $statusLabel}
                <option value="{$statusKey}" {if (string)$statusKey === $form->status} selected {/if}>{$statusLabel}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group col-md-2">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
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
              <th style="width: 10%;"> {Yii::t('app', 'title')} </th>
              <th style="width: 25%;"> {Yii::t('app', 'description')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'due_date')} </th>
              <th style="width: 15%;"> {Yii::t('app', 'assignee')} </th>
              <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
              {if (!$models) }
              <tr><td colspan="6">{Yii::t('app', 'no_data_found')}</td></tr>
              {/if}
              {foreach $models as $key => $model}
              <tr>
                <td style="vertical-align: middle;">{$pages->offset + 1 + $key}</td>
                <td style="vertical-align: middle;">{$model->title}</td>
                <td style="vertical-align: middle;">{$model->description}</td>
                <td style="vertical-align: middle;">{$model->getDueDate(true, 'Y-m-d')}</td>
                <td style="vertical-align: middle;">{$model->getReceiverName()}</td>
                <td style="vertical-align: middle;">
                  <a href='{url route="task/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
                </td>
              </tr>
              {/foreach}
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
$('.find-user').select2({
  ajax: {
    delay: 500,
    allowClear: true,
    url: '{/literal}{$links.user_suggestion}{literal}',
    type: "GET",
    dataType: 'json',
    processResults: function (data) {
      // Tranforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data.items
      };
    }
  }
});
{/literal}
{/registerJs}