{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
{$this->registerCssFile('@web/vendor/assets/pages/css/pricing.min.css', ['depends' => [\backend\assets\AppAsset::className()]])}

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'pricing_coin')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'pricing_coin')}</h1>
<!-- END PAGE TITLE-->
<div class="pricing-content-2">
  <div class="pricing-table-container">
    <div class="row">
      {foreach $models as $model}
      <div class="col-md-3">
        <div class="price-column-container border-left border-top border-right {if $model->isBest()}featured-price{/if}">
          {if $model->isBest()}
          <div class="price-feature-label uppercase bg-red">Best choice</div>
          {/if}
          <div class="price-table-head price-1">
            <h2 class="uppercase bg-blue-ebonyclay font-grey-cararra opt-pricing-5">{$model->title}</h2>
          </div>
          <div class="price-table-pricing">
            <h3>
              <span class="price-sign">$</span>{$model->amount}
            </h3>
            <p class="uppercase">per {$model->num_of_coin} King Coin</p>
          </div>
          <div class="price-table-content">
            <div class="row no-margin">
              <div class="col-xs-12 text-left uppercase">{$model->description}</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="fa fa-credit-card"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">{$model->num_of_coin} King Coin</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                {if $model->isVisible()}
                <i class="fa fa-eye"></i>
                {else}
                <i class="fa fa-eye-slash"></i>
                {/if}
              </div>
              <div class="col-xs-9 text-left uppercase">
                {if $model->isVisible()}
                <span class="label label-sm label-success">{Yii::t('app', 'visible')}</span>
                {else}
                <span class="label label-sm label-default">{Yii::t('app', 'invisible')}</span>
                {/if}
              </div>
            </div>
          </div>
          <div class="price-table-footer">
            {if !$model->isBest()}
            <a href="{url route='pricing-coin/set-best' id=$model->id}" class="btn red">{Yii::t('app', 'set_best')}</a>
            {/if}
            <a href="{url route='pricing-coin/edit' id=$model->id}" class="btn green">{Yii::t('app', 'edit')}</a>
          </div>
        </div>
      </div>
      {/foreach}
    </div>
  </div>
</div>
{registerJs}
{literal}
$(".delete-coin").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_disable_coin')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-coin").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_enable_coin')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}