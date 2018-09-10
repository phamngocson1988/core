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
            <a class="btn purple" href="{url route='task/index' assignee=$app->user->id status=['new', 'inprogress']}">{Yii::t('app', 'my_open_tasks')}</a>
          </div>
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
              <select class="bs-select form-control" name="status[]" multiple="true" >
                {foreach $form->getStatus() as $statusKey => $statusLabel}
                <option value="{$statusKey}" {if in_array($statusKey, $form->status)} selected {/if}>{$statusLabel}</option>
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
              <th style="width: 5%;"> {Yii::t('app', 'avatar')} </th>
              <th style="width: 10%;"> {Yii::t('app', 'title')} </th>
              <th style="width: 30%;"> {Yii::t('app', 'description')} </th>
              <th style="width: 10%;"> {Yii::t('app', 'due_date')} </th>
              <th style="width: 10%;"> {Yii::t('app', 'assignee')} </th>
              <th style="width: 10%;"> {Yii::t('app', 'percent')} </th>
              <th style="width: 10%;"> {Yii::t('app', 'status')} </th>
              <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
              {if (!$models) }
              <tr><td colspan="8">{Yii::t('app', 'no_data_found')}</td></tr>
              {/if}
              {foreach $models as $key => $model}
              <tr>
                <td style="vertical-align: middle;">{$pages->offset + 1 + $key}</td>
                <td style="vertical-align: middle;">
                  {if ($model->receiver)}
                  <img class="img-circle" src="{$model->receiver->getAvatarUrl('50x50')}" /> 
                  {else}
                  <img class="img-circle" src="../vendor/assets/pages/media/profile/profile_user.jpg" width="50" height="50" /> 
                  {/if}
                </td>
                <td style="vertical-align: middle;">{$model->title}</td>
                <td style="vertical-align: middle;">{$model->description}</td>
                <td style="vertical-align: middle;">{$model->getDueDate(true, 'Y-m-d')}</td>
                <td style="vertical-align: middle;">{$model->getReceiverName()}</td>
                <td style="vertical-align: middle; text-align: center;">
                  {$model->percent} %
                  <div class="progress progress-striped active">
                      <div class="progress-bar {if $model->isDelay()}progress-bar-danger{else}progress-bar-success{/if}" role="progressbar" aria-valuenow="{$model->percent}" aria-valuemin="0" aria-valuemax="100" style="width: {$model->percent}%">
                          <span class="sr-only"> {$model->percent}% Complete (success) </span>
                      </div>
                  </div>
                </td>
                <td style="vertical-align: middle;">
                  {$labelClasses = ['new' => 'label-primary', 'inprogress' => 'label-info', 'done' => 'label-success', 'invalid' => 'label-default']}
                  {$labelClass = $labelClasses[$model->status]}
                  <span class="label {$labelClass}">{$model->getStatusLabel()}</span>
                  
                </td>
                <td style="vertical-align: middle;">
                  <a href='{url route="task/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="{Yii::t('app', 'edit_task')}"><i class="fa fa-pencil"></i></a>
                  <a class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="{Yii::t('app', 'view')}" data-toggle="modal" href="#todo-task-modal" data-object='{['id'=>$model->id, 'title'=>$model->title, 'description'=>$model->description, 'due_date'=>$model->due_date, 'assignee_name'=>$model->getReceiverName(), 'status'=>$model->status]|@json_encode nofilter}'><i class="fa fa-eye"></i></a>
                  <a href='{url route="task/update-status" id=$model->id status="inprogress" ref=$ref}' class="btn btn-xs grey-salsa change-status tooltips" data-container="body" data-original-title="{Yii::t('app', change_to_inprogress)}"><i class="fa fa-play-circle"></i></a>
                  <a href='{url route="task/update-status" id=$model->id status="done" ref=$ref}' class="btn btn-xs grey-salsa change-status tooltips" data-container="body" data-original-title="{Yii::t('app', change_to_done)}"><i class="fa fa-check"></i></a>
                  <a href='{url route="task/update-status" id=$model->id status="invalid" ref=$ref}' class="btn btn-xs grey-salsa change-status tooltips" data-container="body" data-original-title="{Yii::t('app', change_to_invalid)}"><i class="fa fa-ban"></i></a>
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
<div id="todo-task-modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel10" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content scroller" style="height: 100%;bottom: auto;" data-always-visible="1" data-rail-visible="0">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <button class="btn btn-square btn-sm green todo-bold todo-inline" id="modal-status-done">{Yii::t('app', 'change_to_done')}</button>
              <button class="btn btn-square btn-sm blue todo-bold todo-inline" id="modal-status-inprogress">{Yii::t('app', 'change_to_inprogress')}</button>
              <button class="btn btn-square btn-sm yellow todo-bold todo-inline" id="modal-status-invalid">{Yii::t('app', 'change_to_invalid')}</button>
              <p class="todo-task-modal-title todo-inline">{Yii::t('app', 'due_date')}:
                  <input class="form-control input-inline input-medium date-picker todo-task-due todo-inline" size="16" type="text" value="10/01/2015" id="modal-due_date"/>
              </p>
              <p class="todo-task-modal-title todo-inline">{Yii::t('app', 'assignee')}:
                  <a class="todo-inline todo-task-assign" href="#todo-members-modal" data-toggle="modal" id="modal-assignee">Luke</a>
              </p>
          </div>
          <div class="modal-body todo-task-modal-body">
              <h3 class="todo-task-modal-bg" id="modal-title">Title</h3>
              <p class="todo-task-modal-bg" id="modal-content">Content</p>
              
          </div>
          <!-- BEGIN PORTLET-->
          <!-- END PORTLET-->
          <div class="modal-footer">
              <button class="btn default" data-dismiss="modal" aria-hidden="true">Close</button>
          </div>
      </div>
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
$(".change-status").ajax_action({
  callback: function(eletement, data) {
    location.reload();
  }
});
$('#todo-task-modal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var object = button.data('object') // Extract info from data-* attributes
  
  var modal = $(this)
  modal.find('#modal-title').text(object.title);
  modal.find('#modal-content').html(object.description);
  modal.find('#modal-due_date').val(object.due_date);
  modal.find('#modal-assignee').html(object.assignee_name);
  var statusClass, statusLabel;
  modal.find('[id^="modal-status-"]').hide();
  modal.find('#modal-status-' + object.status).show();
})
{/literal}
{/registerJs}