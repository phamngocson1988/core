<div class="col-lg-6 col-xs-12 col-sm-12" id="{$widget_id}">
  <div class="portlet light bordered">
    <div class="portlet-title tabbable-line">
      <div class="caption">
        <i class="icon-bubbles font-dark hide"></i>
        <span class="caption-subject font-dark bold uppercase">{Yii::t('app', 'tasks')}</span>
      </div>
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#system_tasks" data-toggle="tab"> {Yii::t('app', 'tasks')} </a>
        </li>
        <li>
          <a href="#my_tasks" data-toggle="tab"> {Yii::t('app', 'my_open_tasks')} </a>
        </li>
      </ul>
    </div>
    <div class="portlet-body">
      <div class="tab-content">
        <div class="tab-pane active" id="system_tasks">
          <!-- BEGIN: Comments -->
          <div class="mt-comments">
            {foreach $models as $model}
            <div class="mt-comment">
              <div class="mt-comment-img">
                {if ($model->receiver)}
                <img class="img-circle" src="{$model->receiver->getAvatarUrl('50x50')}" /> 
                {else}
                <img class="img-circle" src="../vendor/assets/pages/media/profile/profile_user.jpg" width="50" height="50" /> 
                {/if}
              </div>
              <div class="mt-comment-body">
                <div class="mt-comment-info">
                  <span class="mt-comment-author">{$model->getReceiverName()}</span>
                  <span class="mt-comment-date">{$model->getDueDate(true, 'F j, Y')}</span>
                </div>
                <div class="mt-comment-text"> 
                  {$model->title} 
                  <div class="progress progress-striped active">
                    <div class="progress-bar {if $model->isDelay()}progress-bar-danger{else}progress-bar-success{/if}" role="progressbar" aria-valuenow="{$model->percent}" aria-valuemin="0" aria-valuemax="100" style="width: {$model->percent}%">
                        <span class="sr-only"> {$model->percent}% Complete (success) </span>
                    </div>
                  </div>
                </div>
                <div class="mt-comment-details">
                  <span class="mt-comment-status {if $model->isDelay()}mt-comment-status-rejected{else}mt-comment-status-pending{/if}">{$model->getStatusLabel()}</span>
                  <ul class="mt-comment-actions">
                    <li>
                      <a href='{url route="task/edit" id=$model->id ref=$ref}'>{Yii::t('app', 'edit_task')}</a>
                    </li>
                    <!--<li>
                      <a data-toggle="modal" href="#todo-task-modal" data-object='{['id'=>$model->id, 'title'=>$model->title, 'description'=>$model->description, 'due_date'=>$model->due_date, 'assignee_name'=>$model->getReceiverName(), 'status'=>$model->status]|@json_encode nofilter}'>{Yii::t('app', 'view_task')}</a>
                    </li>-->
                    <li>
                      <a class='task-change-status' href='{url route="task/update-status" id=$model->id status="inprogress" ref=$ref}'>{Yii::t('app', 'change_to_inprogress')}</a>
                    </li>
                    <li>
                      <a class='task-change-status' href='{url route="task/update-status" id=$model->id status="done" ref=$ref}'>{Yii::t('app', 'change_to_done')}</a>
                    </li>
                    <li>
                      <a class='task-change-status' href='{url route="task/update-status" id=$model->id status="invalid" ref=$ref}'>{Yii::t('app', 'change_to_invalid')}</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            {/foreach}
          </div>
          <div class="scroller-footer">
            <div class="task-footer">
              <div class="btn-arrow-link pull-right">
                <a href="{url route='task/index'}">{Yii::t('app', 'see_all_records')}</a>
                <i class="icon-arrow-right"></i>
              </div>
            </div>
          </div>
          <!-- END: Comments -->
        </div>
        <div class="tab-pane" id="my_tasks">
          <!-- BEGIN: Comments -->
          <div class="mt-comments">
            {foreach $myTaskModels as $model}
            <div class="mt-comment">
              <div class="mt-comment-img">
                {if ($model->receiver)}
                <img class="img-circle" src="{$model->receiver->getAvatarUrl('50x50')}" /> 
                {else}
                <img class="img-circle" src="../vendor/assets/pages/media/profile/profile_user.jpg" width="50" height="50" /> 
                {/if}
              </div>
              <div class="mt-comment-body">
                <div class="mt-comment-info">
                  <span class="mt-comment-author">{$model->getReceiverName()}</span>
                  <span class="mt-comment-date">{$model->getDueDate(true, 'F j, Y')}</span>
                </div>
                <div class="mt-comment-text"> 
                  {$model->title} 
                  <div class="progress progress-striped active">
                    <div class="progress-bar {if $model->isDelay()}progress-bar-danger{else}progress-bar-success{/if}" role="progressbar" aria-valuenow="{$model->percent}" aria-valuemin="0" aria-valuemax="100" style="width: {$model->percent}%">
                        <span class="sr-only"> {$model->percent}% Complete (success) </span>
                    </div>
                  </div>
                </div>
                <div class="mt-comment-details">
                  <span class="mt-comment-status {if $model->isDelay()}mt-comment-status-rejected{else}mt-comment-status-pending{/if}">{$model->getStatusLabel()}</span>
                  <ul class="mt-comment-actions">
                    <li>
                      <a href='{url route="task/edit" id=$model->id ref=$ref}'>{Yii::t('app', 'edit_task')}</a>
                    </li>
                    <!--<li>
                      <a data-toggle="modal" href="#todo-task-modal" data-object='{['id'=>$model->id, 'title'=>$model->title, 'description'=>$model->description, 'due_date'=>$model->due_date, 'assignee_name'=>$model->getReceiverName(), 'status'=>$model->status]|@json_encode nofilter}'>{Yii::t('app', 'view_task')}</a>
                    </li>-->
                    <li>
                      <a class='task-change-status' href='{url route="task/update-status" id=$model->id status="inprogress" ref=$ref}'>{Yii::t('app', 'change_to_inprogress')}</a>
                    </li>
                    <li>
                      <a class='task-change-status' href='{url route="task/update-status" id=$model->id status="done" ref=$ref}'>{Yii::t('app', 'change_to_done')}</a>
                    </li>
                    <li>
                      <a class='task-change-status' href='{url route="task/update-status" id=$model->id status="invalid" ref=$ref}'>{Yii::t('app', 'change_to_invalid')}</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            {/foreach}
          </div>
          <div class="scroller-footer">
            <div class="task-footer">
              <div class="btn-arrow-link pull-right">
                <a href="{url route='task/index' assignee=$app->user->id status=['new', 'inprogress']}">{Yii::t('app', 'see_all_records')}</a>
                <i class="icon-arrow-right"></i>
              </div>
            </div>
          </div>
          <!-- END: Comments -->
        </div>
      </div>
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
</div>
  
{registerJs}
{literal}
$('#todo-task-modal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var object = button.data('object') // Extract info from data-* attributes
  console.log(object);
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
