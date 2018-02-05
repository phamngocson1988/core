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
          <span>Manage Product Categories</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Product Categories</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Manage Product Categories</span>
            </div>
            <div class="actions">
              <div class="btn-group btn-group-devided">
                <a class="btn green" href="{url route='product/create-category' ref=$ref}">Add new</a>
              </div>
            </div>
          </div>
          <div class="portlet-body">
            {Pjax}
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th style="width: 5%;"> No </th>
                  <th style="width: 20%;"> Image </th>
                  <th style="width: 25%;"> Title </th>
                  <th style="width: 25%;"> Parent </th>
                  <th style="width: 10%;"> Status </th>
                  <th style="width: 15%;" class="dt-center"> Actions </th>
                </tr>
              </thead>
              <tbody>
                  {if (!$models) }
                  <tr><td colspan="6">No data found</td></tr>
                  {/if}
                  {foreach $models as $key => $model}
                  <tr>
                    <td style="vertical-align: middle;">{1 + $key}</td>
                    <td style="vertical-align: middle;"><img src="{$model->getImageUrl('100x100')}" width="120px;" /></td>
                    <td style="vertical-align: middle;">{$model->name}</td>
                    <td style="vertical-align: middle;">{$model->getParentName()}</td>
                    <td style="vertical-align: middle;">{$model->getVisibleLable()}</td>
                    <td style="vertical-align: middle;">
                        <a href='{url route="product/edit-category" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
                        <a href='{url route="product/delete-category" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa delete-action"><i class="fa fa-trash-o"></i></a>
                        <a href="javascript:;" target="_blank" class="btn btn-xs grey-salsa"><i class="fa fa-eye"></i></a>
                    </td>
                  </tr>
                  {/foreach}
              </tbody>
            </table>
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
  confirm_text: 'Do you want to delete this category?',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}