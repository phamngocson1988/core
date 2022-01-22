<?php 
use yii\helpers\Url;
?>
<!-- Hotnews -->
<div class="container">
  <div class="hotnews mt-4 d-flex justify-content-start align-items-center">
    <h3 class="block-title mr-3 mb-0">
      <img  width="25" class="mb-2" src="/images/icon/hotnews.png"/>
      <span>HOT NEWS:</span>
    </h3>
    <span class="post-title">
      <a href="<?=Url::to(['post/view', 'id' => $model->id, 'slug' => $model->slug]);?>"><?=$model->title;?></a>
    </span>
  </div>
</div>