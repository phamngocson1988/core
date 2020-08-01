<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<?php foreach ($models as $thread) : ?>
<li id="thread<?=$thread->id;?>">
  <div class="col-avatar"><a class="user-photo" href="javascript:;"><img src="<?=$thread->sender->getAvatarUrl('50x50');?>" alt="Username"></a></div>
  <div class="col-content">
    <div class="message-title"><a class="mailthread-item" href="<?=Url::to(['mail/view', 'id' => $thread->id]);?>"><?=$thread->subject;?></a></div>
    <div class="message-info">
      <div class="sender"><a href="#"><?=$thread->sender->username;?></a></div>
      <div class="date"><?=TimeElapsed::timeElapsed($thread->updated_at);?></div>
    </div>
  </div>
</li>
<?php endforeach;?>