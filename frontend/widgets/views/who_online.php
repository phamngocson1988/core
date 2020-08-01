<?php 
use yii\helpers\Url;
?>
<section class="forum-online widget-box">
  <h3 class="widget-head"><span class="online-title">Who's Online</span><span class="online-stat"><?=number_format($total);?> MEMBER</span></h3>
  <ul class="online-list widget-inner">
  	<?php foreach ($logs as $log) :?>
  	<?php $user = $log->user;?>
    <li><a href="<?=Url::to(['member/index', 'username' => $user->username]);?>"><?=$user->getName();?></a></li>
	<?php endforeach;?>
  </ul>
</section>