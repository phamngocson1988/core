<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<!-- Product Page-->
<section class="section section-lg bg-default">
  <!-- section wave-->
  <div class="container container-bigger product-single">
    <div class="row row-fix justify-content-sm-center justify-content-lg-between row-30 align-items-lg-center">
      <div class="col-lg-5 col-xl-6 col-xxl-5">
        <div class="product-single-preview">
          <div class="unit flex-column flex-md-row align-items-md-center unit-spacing-md-midle unit--inverse unit-sm">
            <div class="unit-body">
              <ul class="product-thumbnails">
                <!-- <li class="active" data-large-image="<?=$model->getImageUrl('420x550');?>"><img src="<?=$model->getImageUrl('54x71');?>" alt="" width="54" height="71"></li> -->
                <li class="active" data-large-image="/images/shop-01-420x550.png"><img src="/images/shop-01-54x71.png" alt="" width="54" height="71"></li>
                <li data-large-image="/images/shop-02-420x550.png"><img src="/images/shop-02-10x71.png" alt="" width="10" height="71"></li>
              </ul>
            </div>
            <div class="unit-right product-single-image">
                    <!-- <div class="product-single-image-element"><img class="product-image-area animateImageIn" src="/images/shop-01-420x550.png" alt=""></div> -->
              <div class="product-single-image-element"><img class="product-image-area animateImageIn" src="<?=$model->getImageUrl('420x550');?>" alt=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-xl-6 col-xxl-6 text-center text-lg-left">
        <div class="heading-5">Joanne Schultz</div>
        <h3><?=$model->title;?></h3>
        <div class="divider divider-default"></div>
        <p class="text-spacing-sm"><?php //$model->excerpt;?></p>
        <ul class="inline-list">
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-star text-secondary-3"></span>
            <p class="text-spacing-sm offset-0">Bestseller<br>2016</p>
          </li>
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-trophy text-secondary-3"></span>
            <p class="text-spacing-sm offset-0">Bestseller<br>2016</p>
          </li>
        </ul>
        <ul class="inline-list">
          <li class="text-middle">
            <h6>$29.00</h6>
          </li>
          <li class="text-middle"><a class="button button-sm button-secondary button-nina" href="shopping-cart.html">add to cart</a></li>
          <li class="text-middle"><a class="button button-sm button-default-outline button-nina" href="#">add to wishlist</a></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<?=\frontend\widgets\CrossSellWidget::widget(['product_id' => $model->id]);?>

<?php
$script = <<< JS
//var f = AjaxFormSubmit();
JS;
$this->registerJs($script);
?>
