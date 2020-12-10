<?php 
use yii\helpers\Url;
?>
<p class="category-title text-center"><?=$operator->name;?><br><?=Yii::t('app', 'bonuses');?></p>
<ul class="list-news-cate">
  <?php foreach ($bonuses as $bonus) : ?>
  <li><a class="trans" href="<?= $bonus->link ? $bonus->link : 'javascript:;';?>" <?php if ($bonus->link):?>target="_blank"<?php endif;?>><span class="icon"><img src="<?=$bonus->getImageUrl('50x50');?>" alt="image"></span><span class="name"><?=$bonus->title;?></span></a></li>
  <?php endforeach;?>
</ul>
<div class="category-button"><a class="trans" href="<?=Url::to(['bonus/index']);?>"><?=Yii::t('app', 'Show all bonuses');?></a></div>