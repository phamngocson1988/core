<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use frontend\models\Promotion;
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-promotions.png" alt="">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="promotion-search">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <form method="GET" autocomplete='off'>
          <input type="text" name="q" value="<?=$q;?>" placeholder="Search">
          <input type="submit" value="">
        </form>
      </div>
    </div>
  </div>
</section>

<section class="promotion-page">
  <div class="container">
    <div class="row">
      <div class="col col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="product-category">
          <ul>
            <?php foreach (Promotion::getCategories() as $key => $value) : ?>
            <li>
              <a class="cus-btn gray-btn <?php if ($cat == $key) echo 'active';?>" href="<?=Url::to(['promotion/index', 'cat' => $key]);?>"><?=$value;?></a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="col col-lg-9 col-md-9 col-sm-12 col-12">
        <div class="product-listing">
          <div class="row">
            <?php foreach ($models as $model) : ?>
            <div class="col col-lg-6 col-md-6 col-sm-12 col-12 product-item">
              <a class="product-image" href="<?=Url::to(['promotion/view', 'id' => $model->id, 'slug' => $model->slug]);?>">
                <div class="overlay">
                  <span class="main-btn">More Info</span>
                </div>
                <img src="<?=$model->getImageUrl('420x210');?>" alt="<?=$model->title;?>">
              </a>
              <a class="product-name" href="<?=Url::to(['promotion/view', 'id' => $model->id]);?>"><?=$model->title;?></a>
            </div>
            <?php endforeach;?>
          </div>
        </div>
        <!-- <div class="product-list-action text-center">
          <a href="javascript:void;" class="main-btn">See More</a>
        </div> -->
      </div>
    </div>
  </div>
</section>