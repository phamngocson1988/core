<?php
use yii\helpers\Url;
?>
<div class="row">
  <div class="col-lg-9 col-lrg-8">
    <div class="row">
      <div class="col-12 col-md-6">
        <div class="row keyvisual-first">
          <?php $post = array_shift($newestNews);?>
          <?php if ($post) : ?>
          <div class="col-12">
            <a class="item-block" href="<?=Url::to(['news/view', 'id' => $post->id, 'slug' => $post->slug]);?>">
              <img class="object-fit" src="<?=$post->getImageUrl('600x400');?>" alt="image">
              <?php $category = $post->category;?>
              <p class="item-category"><?=$category ? $category->title : '';?></p>
              <p class="item-title"><?=$post->title;?></p>
            </a>
          </div>
          <?php endif;?>
          <?php $post = array_shift($newestNews);?>
          <?php if ($post) : ?>
          <div class="col-6"><a class="item-block" href="<?=Url::to(['news/view', 'id' => $post->id, 'slug' => $post->slug]);?>">
            <img class="object-fit" src="<?=$post->getImageUrl('600x400');?>" alt="image">
              <?php $category = $post->category;?>
              <p class="item-category"><?=$category ? $category->title : '';?></p>
              <p class="item-title"><?=$post->title;?></p>
            </a>
          </div>
          <?php endif;?>
          <?php $post = array_shift($newestNews);?>
          <?php if ($post) : ?>
          <div class="col-6">
            <a class="item-block" href="<?=Url::to(['news/view', 'id' => $post->id, 'slug' => $post->slug]);?>">
              <img class="object-fit" src="<?=$post->getImageUrl('600x400');?>" alt="image">
              <?php $category = $post->category;?>
              <p class="item-category"><?=$category ? $category->title : '';?></p>
              <p class="item-title"><?=$post->title;?></p>
            </a>
          </div>
          <?php endif;?>
        </div>
      </div>
      <?php $post = array_shift($newestNews);?>
      <div class="col-12 col-md-6">
        <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_BANNERHOME]);?>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-lrg-2">
    <?=\frontend\widgets\TopOperatorWidget::widget();?>
  </div>
</div>