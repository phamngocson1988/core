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
      <span>{Yii::t('module.shop', 'manage_products')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('module.shop', 'manage_products')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('module.shop', 'manage_products')}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='product/create' ref=$ref}">{Yii::t('module.shop', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-2">
              <label>{Yii::t('module.shop', 'status')}</label>
              <select name="status" aria-controls="sample_1" class="form-control">
                <option value="">{Yii::t('module.shop', 'all')}</option>
                {foreach $form->getCategories() as $categoryId => $categoryName}
                <option value="{$categoryId}">{$categoryName}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>{Yii::t('module.shop', 'keyword')}: </label> <input type="search" class="form-control" placeholder="{Yii::t('module.shop', 'keyword')}" name="q" value="{$form->q}">
            </div>
            <div class="form-group col-md-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> {Yii::t('module.shop', 'search')}
              </button>
            </div>
          </form>
        </div>
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Yii::t('module.shop', 'no')} </th>
              <th style="width: 10%;"> {Yii::t('module.shop', 'image')} </th>
              <th style="width: 25%;"> {Yii::t('module.shop', 'title')} </th>
              <th style="width: 15%;"> {Yii::t('module.shop', 'categories')} </th>
              <th style="width: 15%;"> {Yii::t('module.shop', 'creator')} </th>
              <th style="width: 10%;" class="dt-center"> {Yii::t('module.shop', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
              {if (!$models) }
              <tr><td colspan="6">{Yii::t('module.shop', 'no_data_found')}</td></tr>
              {/if}
              {foreach $models as $key => $model}
              <tr>
                <td style="vertical-align: middle;">{$pages->offset + 1 + $key}</td>
                <td style="vertical-align: middle;"><img src="{$model->getImageUrl('100x100')}" width="120px;" /></td>
                <td style="vertical-align: middle;">{$model->title}</td>
                <td style="vertical-align: middle;">
                  {foreach $model->categories as $category}
                  <span class="label label-sm label-success">{$category->name}</span>
                  {/foreach}
                </td>
                <td style="vertical-align: middle;">{$model->getCreatorName()}</td>
                <td style="vertical-align: middle;">
                    <a href='{url route="product/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
                    <a href='{url route="product/delete" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa delete-action"><i class="fa fa-trash-o"></i></a>
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
{registerJs}
{literal}
$(".delete-action").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('module.shop', 'confirm_delete_product')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}