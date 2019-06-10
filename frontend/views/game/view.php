<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\widgets\LoginPopupWidget;
use yii\widgets\Pjax;

$game = $item->getGame();
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
                <li class="active" data-large-image="<?=$game->getImageUrl('420x550');?>"><img src="<?=$game->getImageUrl('100x100');?>" alt="" width="95" height="95"></li>
                <?php foreach ($game->images as $image) :?>
                <li class="active" data-large-image="<?=$image->getImageUrl('420x550');?>"><img src="<?=$image->getImageUrl('100x100');?>" alt="" width="95" height="95"></li>
                <?php endforeach ;?>
              </ul>
            </div>
            <div class="unit-right product-single-image">
              <div class="product-single-image-element"><img class="product-image-area animateImageIn" src="<?=$game->getImageUrl('420x550');?>" alt=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-xl-6 col-xxl-6 text-center text-lg-left">
        <?php Pjax::begin(); ?>
        <?php $form = ActiveForm::begin(['id' => 'add-to-cart', 'class' => 'rd-mailform form-fix', 'options' => ['data-pjax' => 'true']]); ?>
        <h3><?=$game->title;?></h3>
        <div class="divider divider-default"></div>
        <p class="text-spacing-sm"><?=$game->excerpt;?></p>
        <ul class="inline-list">
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-coin text-secondary-3"></span>
            <p class="text-spacing-sm offset-0">Price<br><h4 id="price"><?=$item->getTotalPrice();?></h4></p>
          </li>
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-trophy text-secondary-3"></span>
            <p class="text-spacing-sm offset-0"><?=ucfirst($item->getUnitName());?><br><h4 id="unit"><?=$item->getTotalPack();?></h4></p>
          </li>
        </ul>
        <ul class="inline-list">
          <li class="text-middle">
            <?= $form->field($item, 'quantity', [
              'options' => ['class' => 'form-wrap box-width-1 shop-input'],
              'inputOptions' => ['class' => 'form-input input-append', 'type' => 'number', 'min' => 1, 'id' => 'quantity'],
              'template' => '{input}{error}'
            ])->textInput() ?>
          </li>
          <li class="text-middle">
            <?= Html::button('Add to cart', ['class' => 'button button-sm button-secondary button-nina', 'data-pjax' => 'false', 'id' => 'add-cart-button']) ?>
          </li>
          <!-- <li class="text-middle"><a class="button button-sm button-default-outline button-nina" href="#">add to wishlist</a></li> -->
        </ul>
        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>
      </div>
    </div>
  </div>
</section>
<?=LoginPopupWidget::widget(['popup_id' => 'account-modal']);?>
<?php
$script = <<< JS
$('body').on('change', "#quantity", function(){
  $(this).closest('form').submit();
});
$('body').on('click', "#add-cart-button", function(){
  var form = $(this).closest('form');
  $.ajax({
      url: '[:addcart_url]',
      type: form.attr('method'),
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (!result.status) {
          if (result.errors) {
            alert(result.errors);
          }
        } else {
          window.location.href = "[:cart_url]";
        }
      },
  });
});
JS;
$script = str_replace("[:addcart_url]", Url::to(['cart/add', 'id' => $item->game_id]), $script);
$script = str_replace("[:cart_url]", Url::to(['cart/index']), $script);
$this->registerJs($script);
?>
