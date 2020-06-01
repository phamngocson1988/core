<?php 
use yii\helpers\Url;
?>
<section class="section-news $class">
  <div class="container">
    <div class="heading-group">
      <h2 class="sec-title"><?=$category->title;?></h2><a class="btn btn-primary trans" href="<?=Url::to(['post/category', 'id' => $category->id]);?>">SEE ALL<i class="fas fa-chevron-right"></i></a>
    </div>
    <div class="row">
      <?php foreach ($posts as $post) :?>
      <div class="col-sm-6 col-md-4 col-lg-3 col-lrg-2 mb-3"><a class="block-news trans" href="<?=Url::to(['post/view', 'id' => $post->id]);?>">
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