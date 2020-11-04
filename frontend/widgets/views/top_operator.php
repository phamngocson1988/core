<?php
use yii\helpers\Url;
?>
<div class="sidebar-category">
  <p class="category-title"><?=Yii::t('app', 'Top operator');?></p>
  <div class="category-inner">
    <ul class="category-list">
      <?php foreach ($topOperators as $operator) : ?>
      <li><a href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>"><span class="category-icon"><img src="<?=$operator->getImageUrl('50x50');?>" alt="icon"></span><span class="category-name"><?=$operator->name;?></span></a></li>
      <?php endforeach;?>
    </ul>
  </div>
</div>