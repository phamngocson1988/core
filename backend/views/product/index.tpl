{use class='yii\widgets\Pjax' type='block'}
{Pjax enablePushState=false enableReplaceState=false}
<table class="table table-striped table-bordered table-hover table-checkable">
  <thead>
    <tr>
      <th style="width: 10%;"> {Yii::t('app', 'image')} </th>
      <th style="width: 20%;"> {Yii::t('app', 'title')} </th>
      <th style="width: 10%;"> {Yii::t('app', 'price')} </th>
      <th style="width: 10%;"> {Yii::t('app', 'unit')} </th>
      <th style="width: 10%;"> {Yii::t('app', 'status')} </th>
      <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
    </tr>
  </thead>
  <tbody>
      {if (!$models) }
      <tr><td colspan="6">{Yii::t('app', 'no_data_found')}</td></tr>
      {/if}
      {foreach $models as $key => $product}
      <tr id="product-row-{$product->id}" row-data='{$product->id}'>
        <td style="vertical-align: middle;">
          <img src="{$product->getImageUrl('50x50')}" width="50px;" />
        </td>
        <td style="vertical-align: middle;" row-data='title'>{$product->title}</td>
        <td style="vertical-align: middle;" row-data='price'>{$product->price}</td>
        <td style="vertical-align: middle;" row-data='unit'>{$product->unit}</td>
        <td style="vertical-align: middle;" row-data='status'>
          {if $product->isVisible()}
          <span class="label label-success">{Yii::t('app', 'visible')}</span>
          {elseif $product->isDisable()}
          <span class="label label-warning">{Yii::t('app', 'disable')}</span>
          {elseif $product->isDeleted()}
          <span class="label label-default">{Yii::t('app', 'deleted')}</span>
          {/if}
        </td>
        <td style="vertical-align: middle;" class='actions'>
          {if !$product->isDeleted()}
          <a href="{url route='product/edit' id=$product->id}" class="btn btn-xs grey-salsa tooltips edit-product" data-container="body" data-original-title="{Yii::t('app', 'edit')}" data-toggle="modal" data-pjax="0"><i class="fa fa-pencil"></i></a>
          {/if}
          {if $product->isVisible()} 
          <a href='{url route="product/disable" id=$product->id}' class="btn btn-xs grey-salsa disable-action tooltips" data-container="body" data-original-title="{Yii::t('app', 'disable')}" data-pjax="0"><i class="fa fa-eye-slash"></i></a>
          {elseif $product->isDisable()}
          <a href='{url route="product/enable" id=$product->id}' class="btn btn-xs grey-salsa enable-action tooltips" data-container="body" data-original-title="{Yii::t('app', 'enable')}" data-pjax="0"><i class="fa fa-eye"></i></a>
          {/if}

          {if !$product->isDeleted()}
          <a href='{url route="product/delete" id=$product->id}' class="btn btn-xs grey-salsa delete-action tooltips" data-container="body" data-original-title="{Yii::t('app', 'delete')}" data-pjax="0"><i class="fa fa-trash-o"></i></a>
          {/if}
        </td>
      </tr>
      {/foreach}
  </tbody>
</table>
{registerJs}
{literal}
$('.actions>a.disable-action,.actions>a.enable-action').ajax_action({
  confirm: true, 
  callback: function(data) {
    $('.product-filter.active').click();
  }
});
$('.actions>a.delete-action').ajax_action({
  confirm: true, 
  confirm_text: 'Do you want to continue this action? This action can not be reverted.',
  callback: function(data) {
    $('.product-filter.active').click();
  }
});
{/literal}
{/registerJs}
{/Pjax}
