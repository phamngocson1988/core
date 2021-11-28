<?php
use yii\helpers\Url;
?>
<!-- Trending topics widget END -->
<div class="row">
    <!-- Recent post widget START -->
    <div class="col-12 col-sm-12 col-lg-12 hot-news">
        <h4 class="mt-4 mb-3">HOT News</h4>
        <!-- Recent post item -->
        <?php foreach ($models as $model) :?>
        <div class="card recent-post mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="post-thumb">
                <a href="<?=Url::to(['post/view', 'id' => $model->id, 'slug' => $model->slug]);?>"><img class="rounded" src="<?=$model->getImageUrl('100x100', '/images/thumb-demo.jpg');?>" alt=""></a>
                </div>
                <div class="post-title">
                <h6><a href="<?=Url::to(['post/view', 'id' => $model->id, 'slug' => $model->slug]);?>" class="btn-link stretched-link text-reset fw-bold"><?=$model->title;?></a></h6>
                <div class="small mt-1"><?=$model->getCreatedAt(true, 'F j, Y');?></div>
                </div>
            </div>
        </div>
        <!-- End recent post item -->
        <?php endforeach;?>
    </div>
</div>
<!-- Sidebar END -->