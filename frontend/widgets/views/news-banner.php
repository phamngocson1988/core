<?php
use yii\helpers\Url;
?>
<div class="row">
  <div class="col-lg-9 col-lrg-8">
    <div class="row">
      <div class="col-12 col-md-6">
        <div class="row keyvisual-first">
          <?php $post = array_shift($newestNews);?>
          <div class="col-12"><a class="item-block" href="<?=Url::to(['post/view', 'id' => $post->id]);?>"><img class="object-fit" src="<?=$post->getImageUrl('600x400');?>" alt="image">
              <?php $category = $post->category;?>
              <?php if ($category) : ?>
              <p class="item-category"><?=$category->title;?></p>
              <?php endif ;?>
              <p class="item-title"><?=$post->title;?></p></a></div>
          <?php $post = array_shift($newestNews);?>
          <div class="col-6"><a class="item-block" href="<?=Url::to(['post/view', 'id' => $post->id]);?>"><img class="object-fit" src="<?=$post->getImageUrl('600x400');?>" alt="image">
              <?php $category = $post->category;?>
              <?php if ($category) : ?>
              <p class="item-category"><?=$category->title;?></p>
              <?php endif ;?>
              <p class="item-title"><?=$post->title;?></p></a></div>
          <?php $post = array_shift($newestNews);?>
          <div class="col-6"><a class="item-block" href="<?=Url::to(['post/view', 'id' => $post->id]);?>"><img class="object-fit" src="<?=$post->getImageUrl('600x400');?>" alt="image">
              <?php $category = $post->category;?>
              <?php if ($category) : ?>
              <p class="item-category"><?=$category->title;?></p>
              <?php endif ;?>
              <p class="item-title"><?=$post->title;?></p></a></div>
        </div>
      </div>
      <?php $post = array_shift($newestNews);?>
      <div class="col-12 col-md-6"><a class="item-block large" href="<?=Url::to(['post/view', 'id' => $post->id]);?>"><img class="object-fit" src="<?=$post->getImageUrl('600x400');?>" alt="image"></a></div>
    </div>
  </div>
  <div class="col-lg-3 col-lrg-2">
    <div class="sidebar-category">
      <p class="category-title"><?=Yii::t('app', 'top_operator');?></p>
      <div class="category-inner">
        <ul class="category-list">
          <?php foreach ($topOperators as $operator) : ?>
          <li><a href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>"><span class="category-icon"><img src="<?=$operator->getImageUrl('50x50');?>" alt="icon"></span><span class="category-name"><?=$operator->name;?></span></a></li>
          <?php endforeach;?>
        </ul>
      </div>
    </div>
  </div>
</div>