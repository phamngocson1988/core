<?php
use yii\helpers\Url;
use frontend\widgets\LinkPager;
?>
<main>
  <section class="section-module">
    <div class="container">
      <div class="heading-group">
        <h1 class="sec-title">NEWEST OPERATOR</h1>
      </div>
      <div class="sec-content">
        <div class="mod-column">
          <div class="row">
            <?php foreach ($operators as $operator) :?>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
              <div class="block-card">
                <div class="card-image"><img class="object-fit" src="<?=$operator->getImageUrl('400x220');?>" alt="image"></div>
                <div class="card-body">
                  <div class="star-rating-group">
                    <div class="star-rating"><span style="width:<?=$operator->averageReviewPercent();?>%"></span></div><span class="star-rating-text"><?=number_format($operator->averageStar(), 1);?></span>
                  </div>
                  <h3 class="card-title"><?=$operator->name;?></h3>
                  <p class="card-desc">Product A, Product B,Product C, Product S</p><a class="btn btn-primary" href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>">JOIN NOW</a>
                </div>
              </div>
            </div>
            <?php endforeach;?>
          </div>
          <div class="pagination-wrap">
            <?=LinkPager::widget([
              'pagination' => $pages, 
              'maxButtonCount' => 1, 
              'hideOnSinglePage' => false,
              'linkOptions' => ['class' => 'page-link'],
              'pageCssClass' => 'page-item',
            ]);?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>