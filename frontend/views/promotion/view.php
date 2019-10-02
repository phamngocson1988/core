<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use frontend\models\Promotion;
?>
<section class="promotion-page product-listing">
  <div class="container">
    <div class="row">
      <div class="col col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="product-category">
          <ul>
            <?php foreach (Promotion::getCategories() as $categoryKey => $categoryTitle): ?>
            <li>
              <a class="cus-btn gray-btn <?php if ($categoryKey == $model->category) : ?>active<?php endif;?>" href="<?=Url::to(['promotion/index', 'cat' => $categoryKey]);?>"><?=$categoryTitle;?></a>
            </li>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
      <div class="col col-lg-9 col-md-9 col-sm-12 col-12">
        <div class="product-promo-detail">
          <div class="row">
            <div class="col col-sm-12 col-md-6">
              <div class="img-wrap">
                <img src="<?=$model->getImageUrl('420x210');?>" alt="">
              </div>
            </div>
            <div class="col col-sm-12 col-md-6 ti-title-content">
              <div class="ti-breadcrumb">
                <a href="<?=Url::to(['promotion/index']);?>">Promotion</a>
                <a href="javascript:void(0)">Hot Product</a>
              </div>
              <h1><?=$model->title;?></h1>
              <!-- <a href="javascript:;" id="claim" class="main-btn" code='<?=$model->code;?>'>Claim now</a> -->
            </div>
            <div class="col-12 ti-main-content content">
              <?=$model->content;?>
              <hr/>
              <?=Yii::$app->settings->get('TermsConditionForm', 'promotion');?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('#claim').on('click', function(){
  var code = $(this).attr('code');
  copyToClipboard(code);
  swal("Promotion code is copied to clipboard.", "", "success");

})
JS;
$this->registerJs($script);
?>