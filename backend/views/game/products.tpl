<table class="table table-striped table-bordered table-hover table-checkable">
  <thead>
    <tr>
      <th style="width: 20%;"> {Yii::t('app', 'title')} </th>
      <th style="width: 10%;"> {Yii::t('app', 'price')} </th>
      <th style="width: 10%;"> {Yii::t('app', 'unit')} </th>
      <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
    </tr>
  </thead>
  <tbody>
      {if (!$products) }
      <tr><td colspan="4">{Yii::t('app', 'no_data_found')}</td></tr>
      {/if}
      {foreach $products as $key => $product}
      <tr id="product-row-{$product->id}" row-data='{$product->id}'>
        <td style="vertical-align: middle;" row-data='title'>{$product->title}</td>
        <td style="vertical-align: middle;" row-data='price'>{$product->price}</td>
        <td style="vertical-align: middle;" row-data='unit'>{$product->unit}</td>
        <td style="vertical-align: middle;" class='actions'>
          <a href='{url route="game/remove-product" id=$product->id}' class="btn btn-xs grey-salsa delete-action tooltips" data-container="body" data-original-title="{Yii::t('app', 'delete')}"><i class="fa fa-trash-o"></i></a>
        </td>
      </tr>
      {/foreach}
  </tbody>
</table>