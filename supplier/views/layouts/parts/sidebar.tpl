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
      <!-- Game -->
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
            <a href="{url route='game/my-game'}" class="nav-link " code='game.my-game'>
            <span class="title">Game của tôi</span>
            </a>
          </li>

          <li class="nav-item  ">
            <a href="{url route='suggest/index'}" class="nav-link " code='suggest.index'>
            <span class="title">Yêu cầu game mới</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Order -->
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-basket"></i>
        <span class="title">Đơn hàng</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  "> 
            <a href="{url route='/order'}" class="nav-link " code='order.index'>
            <span class="title">Đơn hàng của tôi</span>
            </a>
          </li>

          <li class="nav-item  ">
            <a href="{url route='order/waiting'}" class="nav-link " code='order.waiting'>
            <span class="title">Đơn hàng mới</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='order/pending'}" class="nav-link " code='order.pending'>
            <span class="title">Đơn hàng pending</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='order/processing'}" class="nav-link " code='order.processing'>
            <span class="title">Đơn hàng processing</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='order/completed'}" class="nav-link " code='order.completed'>
            <span class="title">Đơn hàng completed</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='order/confirmed'}" class="nav-link " code='order.confirmed'>
            <span class="title">Đơn hàng confirmed</span>
            </a>
          </li>

        </ul>
      </li>

      <!-- Ngân hàng -->
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-wallet"></i>
        <span class="title">Tài khoản</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  "> 
            <a href="{url route='/bank'}" class="nav-link " code='bank.index'>
            <span class="title">Tài khoản ngân hàng</span>
            </a>
          </li>
          <li class="nav-item  "> 
            <a href="{url route='/withdraw'}" class="nav-link " code='withdraw.index'>
            <span class="title">Yêu cầu rút tiền</span>
            </a>
          </li>
          <li class="nav-item  "> 
            <a href="{url route='/wallet'}" class="nav-link " code='wallet.index'>
            <span class="title">Ví tiền</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</div>