<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
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
            <li>
              <a class="cus-btn gray-btn" href="javascript:void;">New Member</a>
            </li>
            <li>
              <a class="cus-btn gray-btn active" href="javascript:void;">Hot Product</a>
            </li>
            <li>
              <a class="cus-btn gray-btn" href="javascript:void;">VIP Member</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col col-lg-9 col-md-9 col-sm-12 col-12">
        <div class="product-listing">
          <div class="row">
            <?php foreach ($models as $model) : ?>
            <div class="col col-lg-6 col-md-6 col-sm-12 col-12 product-item">
              <a class="product-image" href="<?=Url::to(['promotion/view', 'id' => $model->id]);?>">
                <div class="overlay">
                  <span class="main-btn">More Info</span>
                </div>
                <img src="<?=$model->getImageUrl('300x300');?>" alt="<?=$model->title;?>">
              </a>
              <a class="product-name" href="<?=Url::to(['promotion/view', 'id' => $model->id]);?>"><?=$model->title;?></a>
            </div>
            <?php endforeach;?>
          </div>
        </div>
        <div class="product-list-action text-center">
          <a href="javascript:void;" class="main-btn">See More</a>
        </div>
      </div>
    </div>
  </div>
</section>