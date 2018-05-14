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
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user"></i>
        <span class="title">{Yii::t('app', 'user')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/user/index'}" class="nav-link " code='user.index'>
            <span class="title">{Yii::t('app', 'users')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/rbac/create-role'}" class="nav-link" code='rbac.create-role'>
            <span class="title">{Yii::t('app', 'create_role')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/rbac/assign-role'}" class="nav-link " code='rbac.assign-role'>
            <span class="title">{Yii::t('app', 'role_permission')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/customer/index'}" class="nav-link " code='customer.index'>
            <span class="title">{Yii::t('app', 'customers')}</span>
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
        <span class="title">{Yii::t('module.shop', 'products')}</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="{url route='/shop/product'}" class="nav-link " code='product.index'>
            <span class="title">{Yii::t('module.shop', 'products')}</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{url route='/shop/category/index'}" class="nav-link " code='product.category.index'>
            <span class="title">{Yii::t('module.shop', 'categories')}</span>
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
        </ul>
      </li>
    </ul>
  </div>
</div>