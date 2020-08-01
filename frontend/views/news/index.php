<?php
use yii\helpers\Url;
?>
<main>
  <div class="section-keyvisual">
    <div class="container">
      <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_TOPHOME]);?>
      <?=\frontend\widgets\NewsBannerWidget::widget();?>
    </div>
  </div>
  <section class="section-news section-white">
    <div class="container">
      <div class="heading-group">
        <h2 class="sec-title">OPERATOR NEWS</h2><a class="btn btn-primary trans" href="javascrip:;">Operator</a>
      </div>
      <div class="row">
        <?php foreach ($operatorNews as $post) : ?>
        <div class="col-sm-6 col-md-4 col-lg-3 col-lrg-2 mb-3"><a class="block-news trans" href="<?=Url::to(['news/view', 'id' => $post->id, 'slug' => $post->slug]);?>">
          <div class="news-image"><img class="object-fit" src="<?=$post->getImageUrl('400x220');?>" alt="image"></div>
          <div class="news-body">
            <p class="mb-0"><?=$post->title;?></p>
          </div>
          <div class="news-date"><?=date("F j, Y", strtotime($post->created_at));?></div></a>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </section>
  <?php $categoryId = array_shift($categoryIds);?>
  <?php if ($categoryId) : ?>
  <?=\frontend\widgets\NewsByCategoryWidget::widget(['categoryId' => $categoryId]);?>
  <?php endif;?>
  <?php $categoryId = array_shift($categoryIds);?>
  <?php if ($categoryId) : ?>
  <?=\frontend\widgets\NewsByCategoryWidget::widget(['categoryId' => $categoryId, 'class' => 'section-white']);?>
  <?php endif;?>
</main>