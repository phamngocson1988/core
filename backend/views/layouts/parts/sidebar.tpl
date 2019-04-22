<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
  <div class="page-sidebar navbar-collapse collapse">
    {if 'main_menu_active'|array_key_exists:$this->params}
    {$main_menu_active = $this->params['main_menu_active']}
    {else}
    {$main_menu_active = 'dashboard'}
    {/if}
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px" main_menu_active='{$main_menu_active}'>
      <li class="sidebar-toggler-wrapper hide">
        <div class="sidebar-toggler">
          <span></span>
        </div>
      </li>
      <li class="sidebar-search-wrapper">
      </li>
      <li class="nav-item start active open">
        <a href="{url route='/site/index'}" class="nav-link nav-toggle" code='dashboard'>
        <i class="icon-home"></i>
        <span class="title">{Yii::t('app', 'dashboard')}</span>
        <span class="selected"></span>
        <span class="arrow open"></span>
        </a>
      </li>
      <li class="heading">
        <h3 class="uppercase">{Yii::t('app', 'features')}</h3>
      </li>
      {if Yii::$app->user->can('admin')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-lock"></i>
        <span class="title">{Yii::t('app', 'user')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <!-- <li class="nav-item  ">
            <a href="{url route='/user/index'}" class="nav-link " code='user.index'>
            <span class="title">{Yii::t('app', 'users')}</span>
            </a>
          </li>-->
          <li class="nav-item  ">
            <a href="{url route='/rbac/role'}" class="nav-link" code='rbac.role'>
            <span class="title">{Yii::t('app', 'role')}</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user"></i>
        <span class="title">{Yii::t('app', 'customers')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/customer/index'}" class="nav-link " code='customer.index'>
            <span class="title">{Yii::t('app', 'customers')}</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user-following"></i>
        <span class="title">{Yii::t('app', 'staffs')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/staff/index'}" class="nav-link " code='staff.index'>
            <span class="title">{Yii::t('app', 'staffs')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/department/index'}" class="nav-link " code='department.index'>
            <span class="title">{Yii::t('app', 'department')}</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-clock"></i>
        <span class="title">{Yii::t('app', 'tasks')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/task/index'}" class="nav-link " code='task.index'>
            <span class="title">{Yii::t('app', 'tasks')}</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title">{Yii::t('app', 'posts')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/post/index'}" class="nav-link " code='post.index'>
            <span class="title">{Yii::t('app', 'posts')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/category/index'}" class="nav-link " code='category.index'>
            <span class="title">{Yii::t('app', 'categories')}</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title">{Yii::t('app', 'games')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/game'}" class="nav-link " code='game.index'>
            <span class="title">{Yii::t('app', 'games')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/promotion'}" class="nav-link " code='promotion.index'>
            <span class="title">Khuyến mãi</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="icon-tag"></i>
          <span class="title">{Yii::t('app', 'pricing_coin')}</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/pricing-coin'}" class="nav-link " code='coin.index'>
            <span class="title">{Yii::t('app', 'pricing_coin')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/pricing-coin/create'}" class="nav-link " code='coin.create'>
            <span class="title">{Yii::t('app', 'add_new')}</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">{Yii::t('app', 'system_logs')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/system-log/index'}" class="nav-link " code='system-log.index'>
            <span class="title">{Yii::t('app', 'system_logs')}</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">{Yii::t('app', 'settings')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/setting/application'}" class="nav-link " code='setting.application'>
            <span class="title">{Yii::t('app', 'application')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/setting/social'}" class="nav-link " code='setting.social'>
            <span class="title">{Yii::t('app', 'social_networks')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/setting/script'}" class="nav-link " code='setting.script'>
            <span class="title">{Yii::t('app', 'header_scripts')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
            <span class="title">Cổng thanh toán</span><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item">
                <a href="{url route='setting/paypal'}" class="nav-link" code='setting.paypal'>
                  <i class="cc-paypal"></i> Paypal
                </a>
              </li>
            </ul
          </li>
        </ul>
      </li>
      {/if}
      
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-basket"></i>
        <span class="title">{Yii::t('app', 'shop')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/order'}" class="nav-link " code='order.index'>
            <span class="title">Đơn hàng</span>
            </a>
          </li>
          {if Yii::$app->user->can('saler') || Yii::$app->user->can('handler')}
          <li class="nav-item  ">
            <a href="{url route='/order/my-order'}" class="nav-link " code='order.mine'>
            <span class="title">Đơn hàng của tôi</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order/my-customer-report'}" class="nav-link " code='order.report'>
            <span class="title">Báo cáo của tôi</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order/new-pending-order'}" class="nav-link " code='order.pending'>
            <span class="title">Đơn hàng mới</span>
            </a>
          </li>
          {/if}
          {if (Yii::$app->user->can('admin'))}
          <li class="nav-item  ">
            <a href="{url route='/order-complain'}" class="nav-link " code='ordercomplain.index'>
            <span class="title">Mẫu phản hồi</span>
            </a>
          </li>
          {/if}
        </ul>
      </li>
      
      {if Yii::$app->user->can('accounting')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-line-chart"></i>
        <span class="title">Báo cáo</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/report'}" class="nav-link " code='report.index'>
            <span class="title">Đơn hàng</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/report/game'}" class="nav-link " code='report.game'>
            <span class="title">Báo cáo theo game</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/report/user'}" class="nav-link " code='report.user'>
            <span class="title">Báo cáo theo nhân viên</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/report/transaction'}" class="nav-link " code='report.transaction'>
            <span class="title">Báo cáo theo giao dịch</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}
    </ul>
  </div>
</div>