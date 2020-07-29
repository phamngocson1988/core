<?php 
use yii\helpers\Url;
?>
<section class="section-contributors widget-box">
  <h3 class="widget-head">Top Contributors</h3>
  <div class="contrib-tab-header"><a class="is-active" href="#week">This Week</a><a href="#month">This Month</a><a href="#year">This Year</a><a href="#all">All Time</a></div>
  <div class="contrib-tab-header-sp">
    <select class="form-control form-control-sm">
      <option value="#week">This Week</option>
      <option value="#month">This Month</option>
      <option value="#year">This Year</option>
      <option value="#all">All Time</option>
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
          <div class="contrib-point"><?=number_format($point);?> Points</div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <?php endforeach;?>
    <div class="contrib-button"><a href="#"></a></div>
  </div>
</section>