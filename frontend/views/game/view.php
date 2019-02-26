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
        <?php $form = ActiveForm::begin(['id' => 'add-to-cart', 'class' => 'rd-mailform form-fix ajax-form-submit', 'action' => ['cart/add']]); ?>
        <!-- <div class="heading-5">Joanne Schultz</div> -->
        <h3><?=$model->title;?></h3>
        <div class="divider divider-default"></div>
        <p class="text-spacing-sm"><?=$model->excerpt;?></p>
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
            <h6 id="price"></h6>
          </li>
          <li class="text-middle">
            <?php 
            $metaData = [];
            foreach ($model->products as $product) {
              $metaData[$product->id] = ['data-price' => $product->price];
            }?>

            <?= $form->field($item, 'id', [
              'options' => ['tag' => false],
              'inputOptions' => ['class' => 'form-input select-filter', 'id' => 'products'],
              'template' => '{input}'
            ])->dropDownList(ArrayHelper::map($model->products, 'id', 'title'), ['options' => $metaData]) ?>
          </li>
          <li class="text-middle">
            <?= $form->field($item, 'quantity', [
              'options' => ['class' => 'form-wrap box-width-1 shop-input'],
              'inputOptions' => ['class' => 'form-input input-append',
                'id' => 'quantity', 
                'type' => 'number', 
                'min' => '1', 
                'max' => '300', 
                'value' => '1'
              ],
              'template' => '{input}'
            ])->textInput() ?>
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
$('form#add-to-cart').on('submit', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        console.log(result);
        if (!result.status) {
          if (!result.user_id) { // Not login
            $('#account-modal').modal('show');
          } else if (result.errors) {
            alert(result.errors);
          }
        } else {
          window.location.href = "[:cart_url]";
        }
      },
  });
  return false;
});

$("#products, #quantity").on('change', function(){
  updatePrice();
});

function updatePrice() {
  var price = $("#products").find("option:selected").data('price');
  var quantity = $("#quantity").val();
  var total = price * quantity;
  $("#price").html(total);
}

$("#products").trigger('change');
JS;
$script = str_replace("[:cart_url]", Url::to(['cart/index']), $script);
$this->registerJs($script);
?>
