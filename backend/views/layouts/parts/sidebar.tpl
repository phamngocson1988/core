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
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/contact/index'}" class="nav-link " code='contact.index'>
            <span class="title">Danh bạ</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/contact/call'}" class="nav-link " code='contact.call'>
            <span class="title">Gọi điện</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/contact/sms'}" class="nav-link " code='contact.sms'>
            <span class="title">Nhắn tin</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/contact/history'}" class="nav-link " code='contact.history'>
            <span class="title">Lịch sử giao dịch</span>
            </a>
          </li>
        </ul>
      </li>
      {if $app->user->can('admin')}
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">Quản lý bộ số</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/dialer/index'}" class="nav-link " code='dialer.index'>
            <span class="title">Bộ số</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user"></i>
        <span class="title">{Yii::t('app', 'user')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/customer/index'}" class="nav-link " code='customer.index'>
            <span class="title">{Yii::t('app', 'customers')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/customer/history'}" class="nav-link " code='customer.history'>
            <span class="title">Lịch sử giao dịch</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">Cấu hình</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/setting/import'}" class="nav-link " code='setting.import'>
            <span class="title">Import</span>
            </a>
          </li>
        </ul>
      </li>
      {/if}
    </ul>
  </div>
</div>