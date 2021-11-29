<?php
use yii\helpers\Url;
$cateColors = ['bg-primary', 'bg-danger', 'bg-warning', 'bg-info'];
?>
<div class="mb-4">
    <h2 class="m-0"></i>Related posts</h2>
</div>
<div class="row gy-4 related-posts">
    <!-- Card item START -->
    <?php foreach ($models as $model) : ?>
    <div class="col-sm-4">
        <div class="card">
            <!-- Card img -->
            <div class="position-relative">
                <img class="card-img" src="<?=$model->getImageUrl('300x300', '/images/thumb-demo.jpg');?>" alt="Card image">
                <div class="card-img-overlay d-flex align-items-start flex-column p-3">
                    <!-- Card overlay bottom -->
                    <div class="w-100 mt-auto">
                        <!-- Card category -->
                        <?php foreach ($model->categories as $category) : ?>
                            <?php 
                                $backgrouds = array_rand($cateColors, 1);
                                $backgroud = $cateColors[$backgrouds];
                            ?>
                            <a href="<?=Url::to(['post/category', 'id' => $category->id, 'slug' => $category->slug]);?>" class="badge <?=$backgroud;?> mb-2"><i class="fas fa-circle me-2 small fw-bold"></i><?=$category->name;?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-3">
                <h4 class="card-title"><a href="<?=Url::to(['post/view', 'id' => $model->id, 'slug' => $model->slug]);?>" class="btn-link text-reset fw-bold"><?=$model->title;?></a></h4>
                <p class="card-text"><?=$model->getExcerpt();?></p>
                <!-- Card info -->
                <ul class="nav nav-divider align-items-center d-none d-sm-inline-block">
                    <li class="nav-item"><?=$model->getCreatedAt(true, 'F j, Y');?></li>
                </ul>
            </div>
        </div>
    </div>
    <?php endforeach;?>
    <!-- Card item END -->
</div>