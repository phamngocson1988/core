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
      <span>{Yii::t('app', 'manage_coins')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'pricing_package')}</h1>
<!-- END PAGE TITLE-->
<div class="pricing-content-2">
  <div class="pricing-table-container">
    <div class="row">
      <div class="col-md-3">
        <div class="price-column-container border-left border-top border-right">
          <div class="price-table-head price-1">
            <h2 class="uppercase bg-blue font-grey-cararra opt-pricing-5">Budget</h2>
          </div>
          <div class="price-table-pricing">
            <h3>
              <span class="price-sign">$</span>24
            </h3>
            <p class="uppercase">per month</p>
          </div>
          <div class="price-table-content">
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-user"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">3 Members</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-drawer"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">50GB Storage</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-screen-smartphone"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">Single Device</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-refresh"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">Monthly Backups</div>
            </div>
          </div>
          <div class="price-table-footer">
            <button type="button" class="btn grey uppercase bold">Sign Up</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="price-column-container border-left border-top border-right">
          <div class="price-table-head price-1">
            <h2 class="uppercase bg-blue-steel font-grey-cararra opt-pricing-5">Solo</h2>
          </div>
          <div class="price-table-pricing">
            <h3>
              <span class="price-sign">$</span>39
            </h3>
            <p class="uppercase">per month</p>
          </div>
          <div class="price-table-content">
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-user"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">5 Members</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-drawer"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">100GB Storage</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-screen-smartphone"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">Single Device</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-refresh"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">Weekly Backups</div>
            </div>
          </div>
          <div class="price-table-footer">
            <button type="button" class="btn grey uppercase bold">Sign Up</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="price-column-container featured-price">
          <div class="price-feature-label uppercase bg-red">Best Value</div>
          <div class="price-table-head price-2">
            <h2 class="uppercase bg-green-jungle font-grey-cararra opt-pricing-5">Start up</h2>
          </div>
          <div class="price-table-pricing">
            <h3>
              <span class="price-sign">$</span>59
            </h3>
            <p class="uppercase">per month</p>
          </div>
          <div class="price-table-content">
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-user-follow"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">20 Members</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-drawer"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">500GB Storage</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-cloud-download"></i>
              </div>
              <div class="col-xs-9 text-left uppercase font-green sbold">Cloud Syncing</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-refresh"></i>
              </div>
              <div class="col-xs-9 text-left uppercase font-green sbold">Daily Backups</div>
            </div>
          </div>
          <div class="price-table-footer">
            <button type="button" class="btn green featured-price uppercase">Get it now!</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="price-column-container border-left border-top border-right">
          <div class="price-table-head price-3">
            <h2 class="uppercase bg-blue-ebonyclay font-grey-cararra opt-pricing-5">enterprise</h2>
          </div>
          <div class="price-table-pricing">
            <h3>
              <span class="price-sign">$</span>128
            </h3>
            <p class="uppercase">per month</p>
          </div>
          <div class="price-table-content">
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-users"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">100 Members</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-drawer"></i>
              </div>
              <div class="col-xs-9 text-left uppercase font-green sbold">2TB Storage</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-cloud-download"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">Cloud Syncing</div>
            </div>
            <div class="row no-margin">
              <div class="col-xs-3 text-right">
                <i class="icon-refresh"></i>
              </div>
              <div class="col-xs-9 text-left uppercase">Weekly Backups</div>
            </div>
          </div>
          <div class="price-table-footer">
            <button type="button" class="btn grey uppercase bold">Sign Up</button>
          </div>
        </div>
      </div>
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