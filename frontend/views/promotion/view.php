<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
?>
<section class="promotion-page">
  <div class="container">
    <div class="row">
      <div class="col col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="product-category">
          <ul>
            <li>
              <a class="cus-btn gray-btn" href="#">New Member</a>
            </li>
            <li>
              <a class="cus-btn gray-btn active" href="#">Hot Product</a>
            </li>
            <li>
              <a class="cus-btn gray-btn" href="#">VIP Member</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col col-lg-9 col-md-9 col-sm-12 col-12">
        <div class="product-promo-detail">
          <div class="row">
            <div class="col col-sm-12 col-md-6">
              <div class="img-wrap">
                <img src="<?=$model->getImageUrl('300x300');?>" alt="">
              </div>
            </div>
            <div class="col col-sm-12 col-md-6 ti-title-content">
              <div class="ti-breadcrumb">
                <a href="#">Promotion</a>
                <a href="#">Hot Product</a>
              </div>
              <h1><?=$model->title;?></h1>
              <a href="#" class="main-btn">Claim now</a>
            </div>
            <div class="col-12 ti-main-content">
              <?=$model->content;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>