{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('module.shop', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('module.shop', 'manage_product_categories')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('module.shop', 'manage_product_categories')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('module.shop', 'manage_product_categories')}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='category/create' ref=$ref}">{Yii::t('module.shop', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Yii::t('module.shop', 'no')} </th>
              <th style="width: 20%;"> {Yii::t('module.shop', 'image')} </th>
              <th style="width: 25%;"> {Yii::t('module.shop', 'title')} </th>
              <th style="width: 25%;"> {Yii::t('module.shop', 'category_parent')} </th>
              <th style="width: 10%;"> {Yii::t('module.shop', 'status')} </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('module.shop', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
              {if (!$models) }
              <tr><td colspan="6">{Yii::t('module.shop', 'no_data_found')}</td></tr>
              {/if}
              {foreach $models as $key => $model}
              <tr>
                <td style="vertical-align: middle;">{1 + $key}</td>
                <td style="vertical-align: middle;"><img src="{$model->getImageUrl('100x100')}" width="120px;" /></td>
                <td style="vertical-align: middle;">{$model->name}</td>
                <td style="vertical-align: middle;">{$model->getParentName()}</td>
                <td style="vertical-align: middle;">{$model->getVisibleLable()}</td>
                <td style="vertical-align: middle;">
                    <a href='{url route="category/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
                    <a href='{url route="category/delete" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa delete-action"><i class="fa fa-trash-o"></i></a>
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
{registerJs}
{literal}
$(".delete-action").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('module.shop', 'confirm_delete_category')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}