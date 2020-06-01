<?php
use yii\helpers\Url;
?>
<main>
  <div class="section-keyvisual">
    <div class="container">
      <a class="trans delineation" href="#"><img class="object-fit" src="/img/top/delineation_bnr_01.jpg" alt="image"></a>
      <?=\frontend\widgets\NewsBannerWidget::widget();?>
    </div>
  </div>
  <section class="section-news section-white">
    <div class="container">
      <div class="heading-group">
        <h2 class="sec-title">OPERATOR NEWS</h2><a class="btn btn-primary trans" href="#">SEE ALL<i class="fas fa-chevron-right"></i></a>
      </div>
      <div class="row">
        <?php foreach ($operatorNews as $post) : ?>
        <div class="col-sm-6 col-md-4 col-lg-3 col-lrg-2 mb-3"><a class="block-news trans" href="<?=Url::to(['news/view', 'id' => $post->id]);?>">
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