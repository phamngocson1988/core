<?php 
use yii\helpers\Url;
?>
<p class="category-title text-center"><?=$operator->name;?><br><?=Yii::t('app', 'news');?>
  <?php foreach ($posts as $post) : ?>
  <li><a class="trans" href="<?=Url::to(['news/view', 'id' => $post->id, 'slug' => $post->slug]);?>"><span class="icon"><img src="<?=$post->getImageUrl('50x50');?>" alt="image"></span><span class="name"><?=$post->title;?></span></a></li>
  <?php endforeach;?>
</ul>
<div class="category-button"><a class="trans" href="<?=Url::to(['news/index']);?>"><?=Yii::t('app', 'Show all news');?></a></div>