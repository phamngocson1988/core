<?php
use yii\helpers\Url; 
?>
<section class="forum-group widget-box">
  <h2 class="forum-title widget-head"><a href="javascript:;"><?=$section->title;?></a></h2>
  <ol class="forum-list">
    <?php foreach ($categories as $category) : ?>

    <li class="forum-row">
      <div class="forum-icon"><img src="../img/forum/forum_icon_01.png" alt="<?=$category->title;?>"></div>
      <div class="forum-content">
        <h3 class="forum-title"><a href="<?=Url::to(['forum/category', 'id' => $category->id, 'slug' => $category->slug]);?>"><?=$category->title;?></a></h3>
        <p class="forum-desc"><?=$category->intro;?></p>
      </div>
      <div class="forum-stat"><span><?=number_format($category->countTopic());?></span>posts</div>
      <div class="forum-post">
        <?php
        $topic = $category->getNewestTopic(); 
        if ($topic) : 
          $user = $topic->creator;
        ?>
        <div class="post-item"><a class="post-author-photo" href="javascript:;"><img src="<?=$user->getAvatarUrl('34x34');?>" alt="<?=$user->getName();?>"></a>
          <div class="post-title"><a href="<?=Url::to(['forum/topic', 'id' => $topic->id, 'slug' => $topic->slug]);?>"><?=$topic->subject;?></a></div>
          <div class="post-author">By <a href="javascript:;"><?=$user->getName();?></a></div>
          <div class="post-date"><?=date("F j, Y", strtotime($topic->created_at));?></div>
        </div>
        <?php endif;?>
      </div>
    </li>
    <?php endforeach;?>
  </ol>
</section>