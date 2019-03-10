<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\widgets\LoginPopupWidget;
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
                <li class="active" data-large-image="<?=$model->getImageUrl('420x550');?>"><img src="<?=$model->getImageUrl('100x100');?>" alt="" width="95" height="95"></li>
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
        <?php $form = ActiveForm::begin(['id' => 'add-to-cart', 'class' => 'rd-mailform form-fix', 'action' => ['cart/index'], 'method' => 'GET']); ?>
        <!-- <div class="heading-5">Joanne Schultz</div> -->
        <h3><?=$model->title;?></h3>
        <div class="divider divider-default"></div>
        <p class="text-spacing-sm"><?=$model->excerpt;?></p>
        <ul class="inline-list">
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-currency-usd text-secondary-3"></span>
            <p class="text-spacing-sm offset-0">Price<br><h4 id="price">0</h4></p>
          </li>
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-trophy text-secondary-3"></span>
            <p class="text-spacing-sm offset-0"><?=ucfirst($model->unit_name);?><br><h4 id="unit">0</h4></p>
          </li>
        </ul>
        <ul class="inline-list">
          <li class="text-middle">
            <select class="form-input select-filter" name="pid" id="products">
              <?php foreach ($model->products as $product) {?>
              <option value="<?=$product->id;?>" data-price="<?=$product->price;?>" data-unit='<?=$product->unit;?>'><?=$product->title;?></option>
              <?php }?>
            </select>
          </li>
          <li class="text-middle">
            <div class="form-wrap box-width-1 shop-input">
              <input class="form-input input-append" id="quantity" type="number" min="1" max="300" value="1" name="qt">
            </div>
          </li>
          <li class="text-middle">
            <?= Html::submitButton('Add to cart', ['class' => 'button button-sm button-secondary button-nina']) ?>
          </li>
          <!-- <li class="text-middle"><a class="button button-sm button-default-outline button-nina" href="#">add to wishlist</a></li> -->
        </ul>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</section>

<?=LoginPopupWidget::widget(['popup_id' => 'account-modal']);?>

<?php
$script = <<< JS

$("#products, #quantity").on('change', function(){
  updatePrice();
});

function updatePrice() {
  var price = $("#products").find("option:selected").data('price');
  var unit = $("#products").find("option:selected").data('unit');
  var quantity = $("#quantity").val();
  var totalPrice = price * quantity;
  var totalUnit = unit * quantity;
  $("#price").html(formatMoney(totalPrice, 0));
  $("#unit").html(formatMoney(totalUnit, 0));
}

$("#products").trigger('change');
JS;
$script = str_replace("[:cart_url]", Url::to(['cart/index']), $script);
$this->registerJs($script);
?>
