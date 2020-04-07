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
        <a href="javascript:;" class="nav-link nav-toggle" code='dashboard'>
        <i class="icon-home"></i>
        <span class="title">{Yii::t('app', 'dashboard')}</span>
        <span class="selected"></span>
        <span class="arrow open"></span>
        </a>
      </li>
      <li class="heading">
        <h3 class="uppercase">{Yii::t('app', 'features')}</h3>
      </li>
      <!-- Ban quản trị -->
      {if Yii::$app->user->can('manager')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-lock"></i>
        <span class="title">Quản lý nhân viên</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="{url route='/user/index'}" class="nav-link " code='user.index'>
            <span class="title">Nhân viên</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{url route='/rbac/role'}" class="nav-link" code='user.role'>
            <span class="title">Vai trò</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{url route='/user/login'}" class="nav-link" code='user.login'>
            <span class="title">Lịch sử đăng nhập</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}

      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user"></i>
        <span class="title">Quản lý khách hàng</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/customer/index'}" class="nav-link " code='customer.index'>
            <span class="title">Khách hàng</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-bank"></i>
        <span class="title">Quản lý ngân hàng</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/bank/index'}" class="nav-link " code='bank.index'>
            <span class="title">Quản lý ngân hàng</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/bank-account/index'}" class="nav-link " code='bankaccount.index'>
            <span class="title">Quản lý tài khoản</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='bank-transaction/index'}" class="nav-link " code='banktransaction.index'>
            <span class="title">Các giao dịch ngân hàng</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='bank-transaction/create-input'}" class="nav-link " code='banktransaction.createinput'>
            <span class="title">Tạo giao dịch nhận tiền</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='bank-transaction/create-output'}" class="nav-link " code='banktransaction.createoutput'>
            <span class="title">Tạo giao dịch chuyển khoản</span>
            </a>
          </li>
          {if Yii::$app->user->can('manager')}
          <li class="nav-item">
              <a href="javascript:;" class="nav-link nav-toggle">
                  Thống kê tiền VNĐ
                  <span class="arrow nav-toggle"></span>
              </a>
              <ul class="sub-menu">
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report' currency='VND'}" class="nav-link " code='banktransaction.VND.report'>
                    <span class="title">Thống kê tất cả</span>
                    </a>
                  </li>
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report-by-bank' currency='VND'}" class="nav-link " code='banktransaction.VND.reportbank'>
                    <span class="title">Thống kê theo ngân hàng</span>
                    </a>
                  </li>
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report-by-account' currency='VND'}" class="nav-link " code='banktransaction.VND.reportaccount'>
                    <span class="title">Thống kê theo tài khoản</span>
                    </a>
                  </li>
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report-by-user' currency='VND'}" class="nav-link " code='banktransaction.VND.reportuser'>
                    <span class="title">Thống kê theo nhân viên</span>
                    </a>
                  </li>
              </ul>
          </li>
          <li class="nav-item">
              <a href="javascript:;" class="nav-link nav-toggle">
                  Thống kê tiền CNY
                  <span class="arrow nav-toggle"></span>
              </a>
              <ul class="sub-menu">
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report' currency='CNY'}" class="nav-link " code='banktransaction.CNY.report'>
                    <span class="title">Thống kê tất cả</span>
                    </a>
                  </li>
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report-by-bank' currency='CNY'}" class="nav-link " code='banktransaction.CNY.reportbank'>
                    <span class="title">Thống kê theo ngân hàng</span>
                    </a>
                  </li>
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report-by-account' currency='CNY'}" class="nav-link " code='banktransaction.CNY.reportaccount'>
                    <span class="title">Thống kê theo tài khoản</span>
                    </a>
                  </li>
                  <li class="nav-item  ">
                    <a href="{url route='bank-transaction/report-by-user' currency='CNY'}" class="nav-link " code='banktransaction.CNY.reportuser'>
                    <span class="title">Thống kê theo nhân viên</span>
                    </a>
                  </li>
              </ul>
          </li>
          <li class="nav-item">
              <a href="javascript:;" class="nav-link nav-toggle">
                  Thống kê số dư tài khoản
                  <span class="arrow nav-toggle"></span>
              </a>
              <ul class="sub-menu">
                  <li class="nav-item  ">
                    <a href="{url route='bank-account/report-balance' currency='VND'}" class="nav-link " code='bankaccount.VND.reportbalance'>
                    <span class="title">Các tài khoản VND</span>
                    </a>
                  </li>
                  <li class="nav-item  ">
                    <a href="{url route='bank-account/report-balance' currency='CNY'}" class="nav-link " code='bankaccount.CNY.reportbalance'>
                    <span class="title">Các tài khoản CNY</span>
                    </a>
                  </li>
              </ul>
          </li>
          {/if}
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-money"></i>
        <span class="title">Quản lý quỹ tiền mặt</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='cash/index'}" class="nav-link " code='cash.index'>
            <span class="title">Quỹ tiền mặt</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='cash-account/index'}" class="nav-link " code='cashaccount.index'>
            <span class="title">Tài khoản tiền mặt</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='cash-transaction/index'}" class="nav-link " code='cashtransaction.index'>
            <span class="title">Giao dịch tiền mặt</span>
            </a>
          </li>
        </ul>
      </li>
      {if Yii::$app->user->can('admin')}
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
        </ul>
      </li>
      {/if}
    </ul>
  </div>
</div>