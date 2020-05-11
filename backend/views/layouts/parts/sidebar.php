<?php
use yii\helpers\Url;
?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
  <div class="page-sidebar navbar-collapse collapse">
    <?php
    if (array_key_exists('main_menu_active', $this->params)) {
      $main_menu_active = $this->params['main_menu_active'];
    } else {
      $main_menu_active = 'dashboard';
    }
    ?>
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px" main_menu_active='<?=$main_menu_active;?>'>
      <li class="sidebar-toggler-wrapper hide">
        <div class="sidebar-toggler">
          <span></span>
        </div>
      </li>
      <li class="sidebar-search-wrapper">
      </li>
      <!-- Bảng thông báo -->
      <li class="nav-item start active open">
        <a href="<?=Url::to(['site/index']);?>" class="nav-link nav-toggle" code='dashboard'>
          <i class="icon-home"></i>
          <span class="title"><?=Yii::t('app', 'dashboard');?></span>
          <span class="selected"></span>
          <span class="arrow open"></span>
        </a>
      </li>
    </ul>
  </div>
</div>