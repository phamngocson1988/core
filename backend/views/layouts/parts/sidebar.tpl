<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
  <!-- BEGIN SIDEBAR -->
  <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
  <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
  <div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    {if 'main_menu_active'|array_key_exists:$this->params}
    {$main_menu_active = $this->params['main_menu_active']}
    {else}
    {$main_menu_active = 'dashboard'}
    {/if}
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px" main_menu_active='{$main_menu_active}'>
      <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
      <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
      <li class="sidebar-toggler-wrapper hide">
        <div class="sidebar-toggler">
          <span></span>
        </div>
      </li>
      <!-- END SIDEBAR TOGGLER BUTTON -->
      <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
      <li class="sidebar-search-wrapper">
        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
        <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
        <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
        <!-- <form class="sidebar-search  " action="page_general_search_3.html" method="POST">
          <a href="javascript:;" class="remove">
          <i class="icon-close"></i>
          </a>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
            <a href="javascript:;" class="btn submit">
            <i class="icon-magnifier"></i>
            </a>
            </span>
          </div>
        </form> -->
        <!-- END RESPONSIVE QUICK SEARCH FORM -->
      </li>
      <li class="nav-item start active open">
        <a href="javascript:;" class="nav-link nav-toggle" code='dashboard'>
        <i class="icon-home"></i>
        <span class="title">Dashboard</span>
        <span class="selected"></span>
        <span class="arrow open"></span>
        </a>
      </li>
      <li class="heading">
        <h3 class="uppercase">Features</h3>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user"></i>
        <span class="title">User</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='user/index'}" class="nav-link " code='user.index'>
            <span class="title">Users</span>
            </a>
          </li>
          <!-- <li class="nav-item  ">
            <a href="{url route='rbac/create-role'}" class="nav-link ">
            <span class="title">Create Role</span>
            </a>
          </li> -->
          <li class="nav-item  ">
            <a href="{url route='rbac/assign-role'}" class="nav-link " code='rbac.assign-role'>
            <span class="title">Role/Permission</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='customer/index'}" class="nav-link " code='customer.index'>
            <span class="title">Customers</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='subscriber/index'}" class="nav-link " code='subscriber.index'>
            <span class="title">Subscriber</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title">Posts</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='post/index'}" class="nav-link " code='post.index'>
            <span class="title">Posts</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='post/category'}" class="nav-link " code='post.category'>
            <span class="title">Categories</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title">Promotions</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='promotion/index'}" class="nav-link " code='promotion.index'>
            <span class="title">News</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='promotion/banner'}" class="nav-link " code='promotion.banner'>
            <span class="title">Banner</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-basket"></i>
        <span class="title">Products</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='product/index'}" class="nav-link " code='product.index'>
            <span class="title">Products</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='product/category'}" class="nav-link " code='product.category'>
            <span class="title">Categories</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">Settings</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='setting/application'}" class="nav-link " code='setting.application'>
            <span class="title">Application</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='setting/bank'}" class="nav-link " code='setting.bank'>
            <span class="title">Bank Accounts</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='setting/social'}" class="nav-link " code='setting.social'>
            <span class="title">Social Networks</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='setting/script'}" class="nav-link " code='setting.script'>
            <span class="title">Header Scripts</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <!-- END SIDEBAR MENU -->
    <!-- END SIDEBAR MENU -->
  </div>
  <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->