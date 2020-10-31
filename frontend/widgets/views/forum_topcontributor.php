<?php 
use yii\helpers\Url;
?>
<section class="section-contributors widget-box">
  <h3 class="widget-head"><?=Yii::t('app', 'Top Contributors');?></h3>
  <div class="contrib-tab-header">
    <a class="is-active" href="#week"><?=Yii::t('app', 'This Week');?></a>
    <a href="#month"><?=Yii::t('app', 'This Month');?></a>
    <a href="#year"><?=Yii::t('app', 'This Year');?></a>
    <a href="#all"><?=Yii::t('app', 'All Time');?></a></div>
  <div class="contrib-tab-header-sp">
    <select class="form-control form-control-sm">
      <option value="#week"><?=Yii::t('app', 'This Week');?></option>
      <option value="#month"><?=Yii::t('app', 'This Month');?></option>
      <option value="#year"><?=Yii::t('app', 'This Year');?></option>
      <option value="#all"><?=Yii::t('app', 'All Time');?></option>
    </select>
  </div>
  <div class="widget-inner">
    <?php foreach ($report as $key => $records) : ?>
    <div class="contrib-tab-content-wrapper <?=$key=='week' ? 'is-active' : '';?>" id="contrib-<?=$key;?>">
      <div class="contrib-tab-content">
        <?php foreach ($records as $record) : ?>
        <?php 
        $point = $record['point'];
        $user = $record['user'];
        ?>
        <div class="contrib-item"><a class="contrib-photo" href="<?=Url::to(['member/index', 'username' => $user->username]);?>"><img src="<?=$user->getAvatarUrl('50x50');?>" alt="<?=$user->getName();?>"></a><a class="contrib-name" href="<?=Url::to(['member/index', 'username' => $user->username]);?>"><?=$user->getName();?></a>
          <div class="contrib-point"><?=number_format($point);?> <?=Yii::t('app', 'Points');?></div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <?php endforeach;?>
    <div class="contrib-button"><a href="#"></a></div>
  </div>
</section>