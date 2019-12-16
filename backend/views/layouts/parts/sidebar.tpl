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

      <!-- Bảng thông báo -->
      <li class="nav-item start active open">
        <a href="{url route='/site/index'}" class="nav-link nav-toggle" code='dashboard'>
          <i class="icon-home"></i>
          <span class="title">{Yii::t('app', 'dashboard')}</span>
          <span class="selected"></span>
          <span class="arrow open"></span>
        </a>
      </li>

      <!-- Ban quản trị -->
      {if Yii::$app->user->can('admin')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-lock"></i>
        <span class="title">{Yii::t('app', 'user')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="{url route='/rbac/index'}" class="nav-link " code='rbac.index'>
            <span class="title">Nhà quản trị</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{url route='/rbac/role'}" class="nav-link" code='rbac.role'>
            <span class="title">{Yii::t('app', 'role')}</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}

      <!-- Khách hàng -->
      {if $app->user->can('saler')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user-following"></i>
        <span class="title">Khách hàng</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/user/index'}" class="nav-link " code='user.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}

      <!-- Reseller -->
      {if $app->user->can('saler')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-link"></i>
          <span class="title">Reseller</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          {if $app->user->can('sale_manager')}
          <li class="nav-item  ">
            <a href="{url route='/reseller/index'}" class="nav-link " code='reseller.index'>
            <span class="title">Danh sách reseller</span>
            </a>
          </li>
          {/if}
        </ul>
      </li>
      {/if}

      <!-- Supplier -->
      {if $app->user->can('admin')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-link"></i>
          <span class="title">Nhà cung cấp</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/supplier/index'}" class="nav-link " code='supplier.index'>
            <span class="title">Danh sách</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}

      <!-- Affiliate -->
      {if $app->user->can('sale_manager')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-link"></i>
          <span class="title">Affiliate</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/affiliate/index'}" class="nav-link " code='affiliate.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/affiliate/request'}" class="nav-link " code='affiliate.request'>
            <span class="title">Yêu cầu hợp tác</span>
            <span class="badge badge-success">{$this->params['new_affiliate_request']}</span>
            </a>
          </li>
          {if $app->user->can('admin')}
          <li class="nav-item  ">
            <a href="{url route='/affiliate/withdraw'}" class="nav-link " code='affiliate.withdraw'>
            <span class="title">Yêu cầu rút tiền</span>
            <span class="badge badge-success">{$this->params['new_commission_withdraw']}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/setting/affiliate_program'}" class="nav-link " code='affiliate.setting'>
            <span class="title">Cài đặt</span>
            </a>
          </li>
          {/if}
        </ul>
      </li>
      {/if}

      <!-- Bài viết -->
      {if ($app->user->can('saler') || $app->user->can('marketing_officer'))}
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
      {/if}

      <!-- Trung tâm hổ trợ -->
      {if ($app->user->can('saler') || $app->user->can('marketing_officer'))}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-question"></i>
        <span class="title">Trung tâm hỗ trợ</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/question/index'}" class="nav-link " code='question.index'>
            <span class="title">Trung tâm hỗ trợ</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/question/category'}" class="nav-link " code='question.category'>
            <span class="title">{Yii::t('app', 'categories')}</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}

      <!-- Kingcoin -->
      {if $app->user->can('sale_manager')}
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
      {/if}

      <!-- System log -->
      {if $app->user->can('admin')}
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
      {/if}

      <!-- Ví Kingcoin -->
      {if $app->user->can('admin')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-wallet"></i>
        <span class="title">Ví Kingcoin</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='wallet/index'}" class="nav-link " code='wallet.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}

      <!-- Giao dịch nạp tiền -->
      {if ($app->user->can('accounting') || $app->user->can('saler'))}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-wallet"></i>
        <span class="title">Giao dịch nạp tiền</span>
        {if $this->params['new_offline_transaction']}
        <span class="badge badge-success">{$this->params['new_offline_transaction']}</span>
        {else}
        <span class="arrow"></span>
        {/if}
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='payment-transaction/index'}" class="nav-link " code='transaction.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='payment-transaction/offline'}" class="nav-link " code='transaction.offline'>
            <span class="title">Chưa thanh toán</span>
            <span class="badge badge-success">{$this->params['new_offline_transaction']}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='payment-transaction/paypal'}" class="nav-link " code='transaction.paypal'>
            <span class="title">Paypal</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='payment-transaction/trash'}" class="nav-link " code='transaction.trash'>
            <span class="title">Thùng rác</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}

      <!-- Cấu hình -->
      {if ($app->user->can('saler') || $app->user->can('marketing_officer'))}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">Cấu hình</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          {if $app->user->can('admin')}
          <li class="nav-item  ">
            <a href="{url route='/setting/application'}" class="nav-link " code='setting.application'>
            <span class="title">Thiết lập nâng cao</span>
            </a>
          </li>
          {/if}
          <li class="nav-item  ">
            <a href="{url route='/setting/top_notice'}" class="nav-link " code='setting.top_notice'>
            <span class="title">Thông báo ở đầu trang</span>
            </a>
          </li>
          {if ($app->user->can('sale_manager') || $app->user->can('marketing_officer'))}
          <li class="nav-item  ">
            <a href="{url route='/setting/social'}" class="nav-link " code='setting.social'>
            <span class="title">{Yii::t('app', 'social_networks')}</span>
            </a>
          </li>
          {/if}
          <!-- <li class="nav-item  ">
            <a href="{url route='/setting/script'}" class="nav-link " code='setting.script'>
            <span class="title">{Yii::t('app', 'header_scripts')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/setting/import'}" class="nav-link " code='setting.import'>
            <span class="title">Imports</span>
            </a>
          </li> -->
          <li class="nav-item  ">
            <a href="{url route='/setting/gallery'}" class="nav-link " code='setting.gallery'>
            <span class="title">Home banner</span>
            </a>
          </li>
          {if $app->user->can('sale_manager')}
          <li class="nav-item  ">
            <a href="{url route='/order-complain'}" class="nav-link " code='ordercomplain.index'>
            <span class="title">Mẫu phản hồi</span>
            </a>
          </li>
          {/if}
          {if $app->user->can('admin')}
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
                <a href="{url route='setting/wechat'}" class="nav-link" code='setting.wechat'>
                  <i class="cc-alipay"></i> Wechat
                </a>
              </li>
              <li class="nav-item">
                <a href="{url route='setting/skrill'}" class="nav-link" code='setting.skrill'>
                  <i class="cc-alipay"></i> Skrill
                </a>
              </li>
              <li class="nav-item">
                <a href="{url route='setting/payoneer'}" class="nav-link" code='setting.payoneer'>
                  <i class="cc-alipay"></i> Payoneer
                </a>
              </li>
              <li class="nav-item">
                <a href="{url route='setting/bitcoin'}" class="nav-link" code='setting.bitcoin'>
                  <i class="cc-bitcoin"></i> Bitcoin
                </a>
              </li>
              <li class="nav-item">
                <a href="{url route='setting/postal-savings-bank-of-china'}" class="nav-link" code='setting.postal-savings-bank-of-china'>
                  <i class="cc-alipay"></i> Postal Savings Bank of China
                </a>
              </li>
            </ul
          </li>
          {/if}
        </ul>
      </li>
      {/if}

      <!-- Game -->
      {if ($app->user->can('saler') || $app->user->can('orderteam_manager') || $app->user->can('marketing_officer'))}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title">{Yii::t('app', 'games')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          {if ($app->user->can('saler') || $app->user->can('marketing_officer'))}
          <li class="nav-item  "> 
            <a href="{url route='/game'}" class="nav-link " code='game.index'>
            <span class="title">{Yii::t('app', 'games')}</span>
            </a>
          </li>
          {/if}

          {if $app->user->can('orderteam_manager')}
          <li class="nav-item  ">
            <a href="{url route='game/provider'}" class="nav-link " code='game.provider'>
            <span class="title">Giá nhà cung cấp</span>
            </a>
          </li>
          {/if}
          
          {if $app->user->can('sale_manager')}
          <li class="nav-item  ">
            <a href="{url route='/promotion' promotion_scenario=Promotion::SCENARIO_BUY_GEMS}" class="nav-link " code='game.promotion'>
            <span class="title">Khuyến mãi</span>
            </a>
          </li>
          {/if}
          {if $app->user->can('saler')}
          <li class="nav-item  ">
            <a href="{url route='game/log'}" class="nav-link " code='game.log'>
            <span class="title">Lịch sử thay đổi giá</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='game/price'}" class="nav-link " code='game.price'>
            <span class="title">Tổng hợp giá bán</span>
            </a>
          </li>
          {/if}
        </ul>
      </li>
      {/if}

      <!-- Đơn hàng -->
      {if $app->user->can('saler') || $app->user->can('orderteam') || $app->user->can('accounting')}
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
          {if $app->user->can('saler') || $app->user->can('accounting')}
          <li class="nav-item  ">
            <a href="{url route='/order/verifying'}" class="nav-link " code='order.verifying'>
            <span class="title">Đơn hàng verifying</span>
            <span class="badge badge-success">{$this->params['new_verifying_order']}</span>
            </a>
          </li>
          {/if}
          <li class="nav-item  ">
            <a href="{url route='/order/pending'}" class="nav-link " code='order.pending'>
            <span class="title">Đơn hàng pending</span>
            <span class="badge badge-success">{$this->params['new_pending_order']}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order/processing'}" class="nav-link " code='order.processing'>
            <span class="title">Đơn hàng processing</span>
            <span class="badge badge-success">{$this->params['processing_order']}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order/completed'}" class="nav-link " code='order.completed'>
            <span class="title">Đơn hàng completed</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order/confirmed'}" class="nav-link " code='order.confirmed'>
            <span class="title">Đơn hàng confirmed</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order/cancelling-order'}" class="nav-link " code='order.cancelling'>
            <span class="title">Đơn hàng cancelling</span>
            <span class="badge badge-success">{$this->params['cancelling_order']}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/order/cancelled-order'}" class="nav-link " code='order.cancelled'>
            <span class="title">Đơn hàng cancelled</span>
            </a>
          </li>
          {if $app->user->can('sale_manager')}
          <li class="nav-item  ">
            <a href="{url route='/order/feedback-order'}" class="nav-link " code='order.feedback'>
            <span class="title">Đơn hàng có feedback</span>
            </a>
          </li>
          {/if}
          
        </ul>
      </li>
      {/if}

      <!-- Thống kê -->
      {$roles = $app->authManager->getRolesByUser($app->user->id)}
      {$roles = array_keys($roles)}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-line-chart"></i>
          <span class="title">Thống kê & báo cáo</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          {if $app->user->can('accounting')}
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
          {if $app->user->can('sale_manager') || $app->user->can('orderteam')}
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê thực hiện đơn hàng</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              {if $app->user->can('orderteam')}
              <li class="nav-item  ">
                <a href="{url route='/report/process-order'}" class="nav-link nav-toggle" code="report.process.order">
                  <span class="title">Theo đơn hàng</span>
                </a>
              </li>
              {/if}
              {if $app->user->can('sale_manager') || $app->user->can('orderteam_manager')}
              <li class="nav-item  ">
                <a href="{url route='/report/process-game'}" class="nav-link nav-toggle" code="report.process.game">
                  <span class="title">Theo game</span>
                </a>
              </li>
              {/if}
              {if $app->user->can('sale_manager') || $app->user->can('orderteam_manager')}
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo reseller</span>
                </a>
              </li>
              {/if}
              {if $app->user->can('orderteam_manager')}
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo nhà cung cấp</span>
                </a>
              </li>
              {/if}
              {if $app->user->can('orderteam')}
              <li class="nav-item  ">
                <a href="{url route='/report/process-user'}" class="nav-link nav-toggle" code="report.process.user">
                  <span class="title">Theo nhân viên</span>
                </a>
              </li>
              {/if}
            </ul>
          </li>
          {/if}
          {if $app->user->can('saler')}
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê bán hàng</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              {if $app->user->can('orderteam_manager')}
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
              {/if}
              <li class="nav-item  ">
                <a href="{url route='/report/sale-user'}" class="nav-link " code='report.sale.user'>
                  <span class="title">Doanh số theo nhân viên</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="{url route='/report/sale-reseller'}" class="nav-link " code='report.sale.reseller'>
                  <span class="title">Doanh số theo reseller</span>
                </a>
              </li>
            </ul>
          </li>
          {/if}
          {if $app->user->can('accounting')}
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