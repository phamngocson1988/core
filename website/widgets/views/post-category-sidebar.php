<?php
use yii\helpers\Url;
?>
<!-- Trending topics widget START -->
<div class="trending-topics">
    <h4 class="mt-4 mb-3">Categories</h4>
    <?php foreach ($categories as $category) :?>
    <!-- Category item -->
    <div class="text-center mb-3 card-bg-scale position-relative overflow-hidden rounded bg-dark-overlay-4 " style="background-image:url(/images/categories-001.jpg); background-position: center left; background-size: cover;">
        <div class="p-3">
        <a href="<?=Url::to(['post/category', 'id' => $category->id, 'slug' => $category->slug]);?>" class="stretched-link btn-link fw-bold text-white h5"><?=$category->name;?></a>
        </div>
    </div>
    <?php endforeach;?>
</div>