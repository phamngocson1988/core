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
          <span>Manage Promotions</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Promotions</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Manage Promotions</span>
            </div>
            <div class="actions">
              <div class="btn-group btn-group-devided">
                <a class="btn green" href="{url route='promotion/create' ref=$ref}">Add new</a>
              </div>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row margin-bottom-10">
              <form method="GET">
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
                  <th style="width: 10%;"> Image </th>
                  <th style="width: 15%;"> Title </th>
                  <th style="width: 10%;"> Type </th>
                  <th style="width: 10%;"> Status </th>
                  <th style="width: 15%;"> From </th>
                  <th style="width: 15%;"> To </th>
                  <th style="width: 10%;"> Creator </th>
                  <th style="width: 10%;" class="dt-center"> Actions </th>
                </tr>
              </thead>
              <tbody>
                  {if (!$models) }
                  <tr><td colspan="6">No data found</td></tr>
                  {/if}
                  {foreach $models as $key => $model}
                  <tr>
                    <td style="vertical-align: middle;">{$pages->offset + 1 + $key}</td>
                    <td style="vertical-align: middle;"><img src="{$model->getImageUrl('100x100')}" width="120px;" /></td>
                    <td style="vertical-align: middle;">{$model->title}</td>
                    <td style="vertical-align: middle;">{$model->getTypeLabel()}</td>
                    <td style="vertical-align: middle;">{$model->getStatusLabel()}</td>
                    <td style="vertical-align: middle;">{$model->getFromDate(true)}</td>
                    <td style="vertical-align: middle;">{$model->getToDate(true)}</td>
                    <td style="vertical-align: middle;">{$model->getCreatorName()}</td>
                    <td style="vertical-align: middle;">
                        <a href='{url route="promotion/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
                        <a href='{url route="promotion/delete" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa delete-action"><i class="fa fa-trash-o"></i></a>
                        <a href="javascript:;" target="_blank" class="btn btn-xs grey-salsa"><i class="fa fa-eye"></i></a>
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
  </div>
</div>
<!-- END CONTENT BODY -->
{registerJs}
{literal}
$(".delete-action").ajax_action({
  confirm: true,
  confirm_text: 'Do you want to delete this promotion?',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}