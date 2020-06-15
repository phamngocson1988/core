<?php
use yii\helpers\Url;
?>

<div class="related-posts">
	<h2 class="sec-title text-center">MORE OPERATOR NEWS</h2>
	<div class="row">
		<?php foreach ($posts as $post) : ?>
	  <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4"><a class="block-news trans" href="<?=Url::to(['news/view', 'id' => $post->id, 'slug' => $post->slug]);?>">
	      <div class="news-image"><img class="object-fit" src="<?=$post->getImageUrl('400x220');?>" alt="image"></div>
	      <div class="news-body">
	        <p class="mb-0"><?=$post->title;?></p>
	      </div>
	      <div class="news-date"><?=date('F j, Y', strtotime($post->created_at));?></div></a>
	  </div>
	  <?php endforeach ;?>
	</div>
</div>