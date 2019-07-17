{use class='backend\models\Promotion'}
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
          <li class="nav-item">
            <a href="{url route='/user/index'}" class="nav-link " code='user.index'>
            <span class="title">Nhà quản trị</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{url route='/user/customer'}" class="nav-link " code='user.customer'>
            <span class="title">Khách hàng</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{url route='/rbac/role'}" class="nav-link" code='rbac.role'>
            <span class="title">{Yii::t('app', 'role')}</span>
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
            <a href="{url route='/promotion' promotion_scenario=Promotion::SCENARIO_BUY_GEMS}" class="nav-link " code='game.promotion'>
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
          <li class="nav-item  ">
            <a href="{url route='/promotion' promotion_scenario=Promotion::SCENARIO_BUY_COIN}" class="nav-link " code='package.promotion'>
            <span class="title">Khuyến mãi</span>
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
            <a href="{url route='/setting/top_notice'}" class="nav-link " code='setting.top_notice'>
            <span class="title">Thông báo ở đầu trang</span>
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
            <a href="{url route='/setting/import'}" class="nav-link " code='setting.import'>
            <span class="title">Imports</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/setting/gallery'}" class="nav-link " code='setting.gallery'>
            <span class="title">Home banner</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order-complain'}" class="nav-link " code='ordercomplain.index'>
            <span class="title">Mẫu phản hồi</span>
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
              <li class="nav-item">
                <a href="{url route='setting/alipay'}" class="nav-link" code='setting.alipay'>
                  <i class="cc-alipay"></i> Aiipay
                </a>
              </li>
              <li class="nav-item">
                <a href="{url route='setting/skrill'}" class="nav-link" code='setting.skrill'>
                  <i class="cc-alipay"></i> Skrill
                </a>
              </li>
              <li class="nav-item">
                <a href="{url route='setting/offline'}" class="nav-link" code='setting.offline'>
                  <i class="cc-offline"></i> Offline
                </a>
              </li>
            </ul
          </li>
        </ul>
      </li>
      {/if}

      {$roles = $app->authManager->getRolesByUser($app->user->id)}
      {$roles = array_keys($roles)}
      {if (array_intersect($roles, ['admin', 'orderteam', 'orderteam_manager', 'saler', 'sale_manager']))}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-basket"></i>
        <span class="title">Quản lý đơn hàng</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/order'}" class="nav-link " code='order.index'>
            <span class="title">Đơn hàng</span>
            </a>
          </li>
          {if Yii::$app->user->can('saler') || Yii::$app->user->can('orderteam')}
          <li class="nav-item  ">
            <a href="{url route='/order/new-pending-order'}" class="nav-link " code='order.new'>
            <span class="title">Đơn hàng mới</span>
            <span class="badge badge-success">{$this->params['new_pending_order']}</span>
            </a>
          </li>
          {/if}
        </ul>
      </li>
      {/if}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-line-chart"></i>
          <span class="title">Thống kê & báo cáo</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          {if (array_intersect($roles, ['admin', 'accounting']))}
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê dòng tiền</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item  ">
                <a href="{url route='/report/finance-transaction'}" class="nav-link nav-toggle" code='report.finance.transaction'>
                  <span class="title">Giao dịch nạp tiền</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="{url route='/report/finance-balance'}" class="nav-link nav-toggle" code='report.finance.balance'>
                  <span class="title">Số dư tài khoản khách hàng</span>
                </a>
              </li>
              {* <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Giao dịch rút tiền</span>
                </a>
              </li> *}
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Số dư tài khoản nhà cung cấp</span>
                </a>
              </li> 
            </ul>
          </li>
          {/if}
          {if (array_intersect($roles, ['admin', 'orderteam_manager']))}
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê thực hiện đơn hàng</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item  ">
                <a href="{url route='/report/process-order'}" class="nav-link nav-toggle" code="report.process.order">
                  <span class="title">Theo đơn hàng</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="{url route='/report/process-game'}" class="nav-link nav-toggle" code="report.process.game">
                  <span class="title">Theo game</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo đại lý</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo reseller</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo nhà cung cấp</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="{url route='/report/process-user'}" class="nav-link nav-toggle" code="report.process.user">
                  <span class="title">Theo nhân viên</span>
                </a>
              </li>
            </ul>
          </li>
          {/if}
          {if (array_intersect($roles, ['admin', 'orderteam']))}
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê bán hàng</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item  ">
                <a href="{url route='/report/sale-order'}" class="nav-link " code='report.sale.order'>
                  <span class="title">Doanh số theo đơn hàng</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="{url route='/report/sale-game'}" class="nav-link " code='report.sale.game'>
                  <span class="title">Doanh số theo game</span>
                </a>
              </li>
              {* <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Doanh số theo đại lý</span>
                </a>
              </li> *}
              <li class="nav-item  ">
                <a href="{url route='/report/sale-user'}" class="nav-link " code='report.sale.user'>
                  <span class="title">Doanh số theo nhân viên</span>
                </a>
              </li>
            </ul>
          </li>
          {/if}
          {if (array_intersect($roles, ['admin', 'accounting']))}
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê chi phí lợi nhuận</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item  ">
                <a href="{url route='/report/cost-order'}" class="nav-link nav-toggle" code='report.cost.order'>
                  <span class="title">Theo đơn hàng</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="{url route='/report/cost-game'}" class="nav-link nav-toggle" code='report.cost.game'>
                  <span class="title">Theo game</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="{url route='/report/cost-user'}" class="nav-link nav-toggle" code='report.cost.user'>
                  <span class="title">Theo nhân viên bán hàng</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo đại lý</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo nhà cung cấp</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo reseller</span>
                </a>
              </li>
            </ul>
          </li>
          {/if}
        </ul>
      </li>
    </ul>
  </div>
</div>