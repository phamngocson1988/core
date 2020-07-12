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
      <!-- Ban quản trị -->
      <?php if (Yii::$app->user->can('system')) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-lock"></i>
        <span class="title"><?=Yii::t('app', 'manage_rbac');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['rbac/index']);?>" class="nav-link " code='rbac.index'>
            <span class="title"><?=Yii::t('app', 'manage_staff');?></span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=Url::to(['rbac/role']);?>" class="nav-link" code='rbac.role'>
            <span class="title"><?=Yii::t('app', 'manage_role');?></span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user-following"></i>
        <span class="title"><?=Yii::t('app', 'manage_user');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['user/index']);?>" class="nav-link " code='user.index'>
            <span class="title"><?=Yii::t('app', 'user_list');?></span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-building"></i>
        <span class="title"><?=Yii::t('app', 'manage_operator');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['operator/index']);?>" class="nav-link " code='operator.index'>
            <span class="title"><?=Yii::t('app', 'operator_list');?></span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=Url::to(['operator/meta']);?>" class="nav-link " code='operator.meta'>
            <span class="title"><?=Yii::t('app', 'operator_meta');?></span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-newspaper-o"></i>
        <span class="title"><?=Yii::t('app', 'manage_post');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['post/index']);?>" class="nav-link " code='post.index'>
            <span class="title"><?=Yii::t('app', 'post_list');?></span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=Url::to(['category/index']);?>" class="nav-link " code='category.index'>
            <span class="title"><?=Yii::t('app', 'category_list');?></span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-newspaper-o"></i>
        <span class="title"><?=Yii::t('app', 'manage_bonus');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['bonus/index']);?>" class="nav-link " code='bonus.index'>
            <span class="title"><?=Yii::t('app', 'bonus_list');?></span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user-following"></i>
        <span class="title"><?=Yii::t('app', 'manage_complain');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['reason/index']);?>" class="nav-link " code='reason.index'>
            <span class="title"><?=Yii::t('app', 'manage_reason');?></span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user-following"></i>
        <span class="title"><?=Yii::t('app', 'manage_forum');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['forum/index']);?>" class="nav-link " code='forum.index'>
            <span class="title"><?=Yii::t('app', 'manage_forum');?></span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=Url::to(['forum-section/index']);?>" class="nav-link " code='forum-section.index'>
            <span class="title"><?=Yii::t('app', 'manage_forum_section');?></span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</div>