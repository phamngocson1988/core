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

      <!-- Đơn hàng -->
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
          {if $app->user->can('saler')}
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
    </ul>
  </div>
</div>