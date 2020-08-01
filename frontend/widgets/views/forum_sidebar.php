<?php
use yii\helpers\Url; 
?>
<div class="side-topics widget-box" style="margin-bottom: 20px">
  <h3 class="widget-head">Topics</h3>
  <div class="widget-inner">
    <ul class="list-posts">
      <?php foreach ($topics as $topic) : ?>
      <?php $user = $topic->creator;?>
      <li class="post-item"><a class="post-author-photo" href="<?=Url::to(['member/index', 'username' => $user->username]);?>"><img src="<?=$user->getAvatarUrl('34x34');?>" alt="<?=$user->getName();?>"></a>
        <div class="post-title"><a href="<?=Url::to(['forum/topic', 'id' => $topic->id, 'slug' => $topic->slug]);?>" class="short-text"><?=$topic->subject;?></a></div>
        <div class="post-author">
          By
          <a href="<?=Url::to(['member/index', 'username' => $user->username]);?>"><?=$user->getName();?></a>
        </div>
        <div class="post-date"><?=date("F j, Y", strtotime($topic->created_at));?></div>
        <div class="post-replies"><span><?=number_format($topic->countPost());?></span></div>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
