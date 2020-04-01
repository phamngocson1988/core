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
            <a href="{url route='/user/index'}" class="nav-link " code='customer.index'>
            <span class="title">Khách hàng</span>
            </a>
          </li>
        </ul>
      </li>
      {if Yii::$app->user->can('admin')}
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
        </ul>
      </li>
      {/if}
    </ul>
  </div>
</div>