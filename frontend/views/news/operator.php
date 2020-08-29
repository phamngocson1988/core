<?php 
use yii\helpers\Url;
use frontend\widgets\LinkPager;
?>
<section class="section-news">
  <div class="container">
    <div class="row">
      <?php foreach ($posts as $post) :?>
      <div class="col-sm-6 col-md-4 col-lg-3 col-lrg-2 mb-3"><a class="block-news trans" href="<?=Url::to(['news/view', 'id' => $post->id, 'slug' => $post->slug]);?>">
        <div class="news-image"><img class="object-fit" src="<?=$post->getImageUrl('400x220');?>" alt="image"></div>
        <div class="news-body">
          <p class="mb-0"><?=$post->title;?></p>
        </div>
        <div class="news-date"><?=date("F j, Y", strtotime($post->created_at));?></div></a>
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
</section>