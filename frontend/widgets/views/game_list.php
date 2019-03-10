<?php
use yii\helpers\Url;
?>
<section class="section section-variant-2 bg-default novi-background bg-cover text-center">
  <div class="container container-wide">
    <div class="isotope-wrap row row-0 row-lg-30">
      <!-- Isotope Filters-->
      <div class="col-xl-12">
        <div class="isotope isotope-md row" data-isotope-layout="fitRows" data-isotope-group="movies" data-lightgallery="group">
          <div class="row">
            <?php foreach ($models as $model) : ?>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="<?=Url::to(['game/view', 'id' => $model->id, 'slug' => $model->slug]);?>"><img class="thumbnail-simple-image" src="<?=$model->getImageUrl('270x400');?>" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="<?=Url::to(['game/view', 'id' => $model->id]);?>"><?=$model->title;?></a></p>
                <p class="thumbnail-simple-subtitle">$ <?=number_format($model->getPrice());?> / <?=number_format($model->getUnit());?> <?=$model->unit_name;?></p>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>