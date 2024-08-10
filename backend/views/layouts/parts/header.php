<?php 
$user = Yii::$app->user->getIdentity();
use backend\components\notifications\Notifications;
use backend\components\notifications\PushNotifications;
use yii\helpers\Url;
?>
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
  <!-- BEGIN HEADER INNER -->
  <div class="page-header-inner ">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
      <a href="index.html">
      <img src="/vendor/assets/layouts/layout/img/logo.png" alt="logo" class="logo-default" /> </a>
      <div class="menu-toggler sidebar-toggler">
        <span></span>
      </div>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
    <span></span>
    </a>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN TOP NAVIGATION MENU -->
    <div class="top-menu">
      <ul class="nav navbar-nav pull-right">
        
    <?=Notifications::widget();?>
        <li class="dropdown dropdown-user">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
          <img alt="Avatar" global="avatar_<?=$user->id;?>" class="img-circle" src="<?=$user->getAvatarUrl('100x100');?>"/>
          <span class="username username-hide-on-mobile"> <?=$user->getName();?> </span>
          <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-default">
            <li>
              <a href="<?=Url::to(['profile/index']);?>">
              <i class="icon-user"></i> <?=Yii::t('app', 'my_profile');?> </a>
            </li>
            <li>
              <a href="<?=Url::to(['profile/password']);?>">
              <i class="icon-key"></i> <?=Yii::t('app', 'change_password');?> </a>
            </li>
            <li>
              <a href="<?=Url::to(['site/logout']);?>">
              <i class="icon-logout"></i> <?=Yii::t('app', 'logout');?> </a>
            </li>
          </ul>
        </li>
        <!-- END QUICK SIDEBAR TOGGLER -->
      </ul>
    </div>
    <!-- END TOP NAVIGATION MENU -->
  </div>
  <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<?php PushNotifications::widget();?>
