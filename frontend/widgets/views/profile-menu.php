<?php
use yii\helpers\Url;
?>
<div class="header-avatar header-dropdown">
  <a class="avatar" href="javascript:;"><img class="img-radius user-avatar" src="<?=$user->getAvatarUrl('50x50');?>" alt="image"></a>
  <span class="js-action"></span>
  <div class="dropdown-mega">
    <div class="dropdown-mega-inner">
      <p class="mega-title">Good afternoon <?=$user->getName();?><i class="fas fa-cog"></i></p>
      <div class="mega-content">
        <div class="mega-row">
          <p class="mega-ttl"><?=Yii::t('app', 'active_complaint');?></p>
          <ul class="list-text">
            <li><a href="#">Henderson &amp; Bench - My deposit is not approved</a></li>
            <li><a href="#">Henderson &amp; Bench - My deposit is not approved</a></li>
            <li><a href="#">Henderson &amp; Bench - My deposit is not approved</a></li>
          </ul>
          <div class="mega-create"><a class="fas fa-plus-circle trans" href="#"></a></div>
        </div>
        <?php if ($operators) : ?>
        <div class="mega-row">
          <p class="mega-ttl"><?=Yii::t('app', 'favorite_operaotor');?></p>
          <ul class="list-favorites">
            <?php foreach ($operators as $operator) : ?>
            <li><a href="<?=Url::to(['operator/view', 'id' => $operator->id]);?>"><img src="<?=$operator->getImageUrl('50x50');?>" alt="image"></a></li>
            <?php endforeach;?>
          </ul>
          <div class="mega-create"><a class="fas fa-plus-circle trans" href="#"></a></div>
        </div>
        <?php endif;?>
      </div>
      <div class="mega-link"><a href="<?=Url::to(['profile/index']);?>" class="text-uppercase"><?=Yii::t('app', 'my_profile');?></a><a class="trans" href="<?=Url::to(['site/logout']);?>"><?=Yii::t('app', 'logout');?></a></div>
    </div>
  </div>
</div>